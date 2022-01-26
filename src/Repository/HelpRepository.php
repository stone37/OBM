<?php

namespace App\Repository;

use App\Entity\Help;
use App\Model\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Help|null find($id, $lockMode = null, $lockVersion = null)
 * @method Help|null findOneBy(array $criteria, array $orderBy = null)
 * @method Help[]    findAll()
 * @method Help[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HelpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Help::class);
    }

    public function getBySlug(string $slug)
    {
        return $this->createQueryBuilder('h')
                ->where('h.enabled = 1')
                ->andWhere('h.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getOneOrNullResult();
    }

    public function search(Search $search)
    {
        $qb = $this->createQueryBuilder('h')
                    ->where('h.enabled = 1')
                    ->andWhere('h.title LIKE :data')
                    //->orWhere('h.content LIKE :data')
                    ->setParameter('data', '%'.$search->getTitle().'%');

        return $qb->getQuery()->getResult();
    }
}

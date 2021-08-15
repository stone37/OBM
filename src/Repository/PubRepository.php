<?php

namespace App\Repository;

use App\Entity\Pub;
use App\Model\Admin\PubSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Pub|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pub|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pub[]    findAll()
 * @method Pub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pub::class);
    }

    public function getOneAdmin($type)
    {
        try
        {
            $qb = $this->createQueryBuilder('p')
                ->where('p.type = :type')
                ->setParameter('type', $type)
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    public function getAdmin(PubSearch $search, $type)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.type = :type')
            ->setParameter('type', $type)
            ->orderBy('p.position', 'asc');

        if ($search->isEnabled())
            $qb->andWhere('p.enabled = 1');

        if ($search->getName())
            $qb->andWhere('p.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');


        return $qb;
    }



}

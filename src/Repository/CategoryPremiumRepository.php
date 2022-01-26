<?php

namespace App\Repository;

use App\Entity\CategoryPremium;
use App\Model\Admin\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryPremium|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryPremium|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryPremium[]    findAll()
 * @method CategoryPremium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryPremiumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryPremium::class);
    }

    public function getCategoryPremiumEnabled()
    {
        $qb = $this->createQueryBuilder('cp');

        $qb->where('cp.enabled = 1')
            ->orderBy('cp.position', 'asc');

        return $qb->getQuery()->getResult();
    }

    public function getAdmin(CategorySearch $search)
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.position', 'asc');

        if ($search->isEnabled())
            $qb->andWhere('c.enabled = 1');

        if ($search->getName())
            $qb->andWhere('c.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');

        return $qb;
    }

    public function findForApi(): array
    {
        $qb = $this->createQueryBuilder('cp');

        $qb->where('cp.enabled = 1')
            ->orderBy('cp.position', 'asc');

        return $qb->getQuery()->getResult();
    }
}

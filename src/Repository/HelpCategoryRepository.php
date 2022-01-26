<?php

namespace App\Repository;

use App\Entity\HelpCategory;
use App\Model\Admin\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HelpCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method HelpCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method HelpCategory[]    findAll()
 * @method HelpCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HelpCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HelpCategory::class);
    }

    public function getAdminWithParent(CategorySearch $search, $parent = null)
    {
        $qb = $this->createQueryBuilder('hc');

        if (!$parent)
            $qb->where($qb->expr()->isNull('hc.parent'));
        else
            $qb->where('hc.parent = :parent')->setParameter('parent', $parent);

        $qb->orderBy('hc.position', 'asc');

        if ($search->isEnabled())
            $qb->andWhere('hc.enabled = 1');

        if ($search->getName())
            $qb->andWhere('hc.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');

        return $qb;
    }

    public function getEnabledQueryBuilder()
    {
        $qb = $this->createQueryBuilder('hc');

        $qb->where('hc.enabled = 1')
            ->leftJoin('hc.children', 'children')
            ->addSelect('children')
            ->orderBy('hc.parent', 'asc');

        return $qb;
    }

    public function getEnabledParent(?HelpCategory $parent)
    {
        $qb = $this->createQueryBuilder('hc');

        if ($parent) {
            $qb->where('hc.parent = :parent')->setParameter('parent', $parent);
        } else {
            $qb->where($qb->expr()->isNull('hc.parent'));
        }

        $qb->andWhere('hc.enabled = 1')
            ->leftJoin('hc.children', 'children')
            ->leftJoin('hc.helps', 'helps')
            ->addSelect('children')
            ->addSelect('helps')
            ->orderBy('hc.position', 'asc');

        return $qb->getQuery()->getResult();
    }

    public function getEnabled()
    {
        $qb = $this->createQueryBuilder('hc')
                ->where('hc.enabled = 1')
                ->orderBy('hc.parent', 'asc');

        return $qb;
    }

    public function findBySlug($slug)
    {
        $qb = $this->createQueryBuilder('hc')
            ->where('hc.enabled = 1')
            ->andWhere('hc.slug = :slug')
            ->leftJoin('hc.children', 'children')
            ->leftJoin('hc.parent', 'parent')
            ->addSelect('children')
            ->addSelect('parent')
            ->setParameter('slug', (string)$slug)
            ->getQuery()->getOneOrNullResult();

        return $qb;
    }
}

<?php

namespace App\Repository;

use App\Entity\Vignette;
use App\Model\Admin\VignetteSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vignette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vignette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vignette[]    findAll()
 * @method Vignette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VignetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vignette::class);
    }

    public function getAdmins(VignetteSearch $search)
    {
        $qb = $this->createQueryBuilder('v');

        if ($search->isEnabled())
            $qb->andWhere('v.enabled = 1');

        return $qb;
    }

    public function getEnabled(string $category, ?string $subCategory)
    {
        $qb = $this->createQueryBuilder('v')
                    ->where('v.enabled = 1')
                    ->andWhere('v.category = :category_slug')
                    ->andWhere('v.subCategory = :sub_category_slug')
                    ->andWhere('v.startDate < NOW()')
                    ->andWhere('v.endDate > NOW()')
                    ->setParameter('category_slug', $category)
                    ->setParameter('sub_category_slug', $subCategory);

        return $qb->getQuery()->getResult();
    }

}

<?php

namespace App\Repository;

use App\Entity\Taxe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Taxe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxe[]    findAll()
 * @method Taxe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxe::class);
    }

    // /**
    //  * @return Tva[] Returns an array of Tva objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tva
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

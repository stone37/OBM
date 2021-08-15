<?php

namespace App\Repository;

use App\Entity\Bannir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bannir|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bannir|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bannir[]    findAll()
 * @method Bannir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bannir::class);
    }

    // /**
    //  * @return Bannir[] Returns an array of Bannir objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bannir
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

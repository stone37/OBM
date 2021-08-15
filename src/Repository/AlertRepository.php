<?php

namespace App\Repository;

use App\Entity\Advert;
use App\Entity\Alert;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    public function getByUser(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'desc');

        return $qb->getQuery()->getResult();
    }

    public function getEnabledByUser(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->where('a.user = :user')
            ->andWhere('a.enabled = 1')
            ->setParameter('user', $user)
            ->orderBy('a.createdAt', 'desc');

        return $qb->getQuery()->getResult();
    }

    public function getUserAlertActiveNumber(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.enabled = 1')
            ->setParameter('user', $user);

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getUserAlertNumber(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.enabled = 0')
            ->setParameter('user', $user);

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getValide(Advert $advert)
    {
        $qb = $this->createQueryBuilder('a')
                ->leftJoin('a.user', 'user')
                ->addSelect('user')
                ->where('a.enabled = 1')
                ->andWhere('a.category = :category')
                ->andWhere('a.subCategory = :subCategory')
                ->setParameter('category', $advert->getCategory())
                ->setParameter('subCategory', $advert->getSubCategory());

        if ($advert->getSubDivision()) {
            $qb->andWhere('a.subDivision = :subDivision')->setParameter('subDivision', $advert->getSubDivision());
        }

        return $qb->getQuery()->getResult();
    }


    // /**
    //  * @return Alert[] Returns an array of Alert objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Alert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

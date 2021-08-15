<?php

namespace App\Repository;

use App\Entity\AdvertRead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdvertRead|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertRead|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertRead[]    findAll()
 * @method AdvertRead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertRead::class);
    }


}

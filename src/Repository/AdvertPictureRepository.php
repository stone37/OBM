<?php

namespace App\Repository;

use App\Entity\AdvertPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdvertPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertPicture[]    findAll()
 * @method AdvertPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertPictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertPicture::class);
    }

}

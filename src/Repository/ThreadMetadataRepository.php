<?php

namespace App\Repository;

use App\Entity\ThreadMetadata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThreadMetadata|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadMetadata|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadMetadata[]    findAll()
 * @method ThreadMetadata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadMetadataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadMetadata::class);
    }


}

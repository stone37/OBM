<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zone[]    findAll()
 * @method Zone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    /**
     * Recherche une zone
     *
     * @param $city
     * @param $query
     * @return int|mixed|string
     */
    public function getZone($city, $query)
    {
        $qb = $this->createQueryBuilder('z')
                ->where('z.enabled = 1')
                ->andWhere('z.city = :city')
                ->andWhere('z.zone LIKE :q')
                ->setParameter('city', $city)
                ->setParameter('q', '%'.$query.'%')
                ->setParameter('city', $city)
                ->setMaxResults(6);

        return $qb->getQuery()->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findByCountryCode(string $code): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
                    ->where('c.countryCode = :code')
                    ->setParameter('code', $code)
                    ->orderBy('c.position', 'asc');

        return $qb;
    }

    public function getCitiesByCountryCode(string $code): ?array
    {
        $results = $this->createQueryBuilder('c')
            ->where('c.countryCode = :code')
            ->setParameter('code', $code)
            ->orderBy('c.position', 'asc')
            ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['name'];

        return $data;
    }

    public function getEnabledCitiesByCountryCode(string $code): ?array
    {
        $results = $this->createQueryBuilder('c')
            ->where('c.enabled = 1')
            ->andWhere('c.countryCode = :code')
            ->setParameter('code', $code)
            ->orderBy('c.position', 'asc')
            ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['name'];

        return $data;
    }
}

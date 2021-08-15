<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Recupere un produit de type option photo
     *
     * @return int|mixed|string|null
     */
    public function getOptionPhoto()
    {
        try
        {
            $qb = $this->createQueryBuilder('p')
                ->where('p.type = 0')
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    /**
     * Recupere des produit de type option visuelle dans l'administration
     *
     * @param $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAdmin($type)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.type = :type')
            ->setParameter('type', $type)
            ->orderBy('p.createdAt', 'desc');

        return $qb;
    }

    /**
     * Recupere des produits dont l'ID est dans un tableau
     *
     * @param $array
     * @return int|mixed|string
     */
    public function findArray($array)
    {
        $qb = $this->createQueryBuilder('p')
            ->Where('p.id IN (:array)')
            ->setParameter('array', $array);

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere des produits de type option visuelle
     *
     * @param $type
     * @return int|mixed|string
     */
    public function getOptionVisualByType($type)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.category = :category')
            ->andWhere('p.type = :type')
            ->setParameter('category', 'ov')
            ->setParameter('type', $type)
            ->orderBy('p.price', 'asc');

        return $qb->getQuery()->getResult();
    }

    public function getMinPrice($type)
    {
        $qb = $this->createQueryBuilder('p')
                ->where('p.type = :type')
                ->setParameter('type', $type)
                ->orderBy('p.price', 'asc')
                ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}

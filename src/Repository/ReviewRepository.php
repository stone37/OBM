<?php

namespace App\Repository;

use App\Entity\Review;
use App\Model\Admin\ReviewSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * Recupere un avis ou suggestion actif
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEnabled()
    {
        return $this->createQueryBuilder('r')
                ->where('r.enabled = 1')
                ->orderBy('r.createdAt', 'desc');
    }

    /**
     * Recupere les d'avis ou de suggestions
     *
     * @param ReviewSearch $search
     * @param $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAdminReviews(ReviewSearch $search, $type)
    {
        $qb = $this->createQueryBuilder('r')
                ->where('r.type = :type')
                ->orderBy('r.createdAt', 'desc')
                ->setParameter('type', $type);

        if ($search->isEnabled())
            $qb->andWhere('r.enabled = 1');

        return $qb;
    }

    /**
     * Recupere le nombre d'avis et suggestion
     *
     * @param $type
     * @return \Doctrine\ORM\QueryBuilder|int|mixed|string
     */
    public function getNumber($type)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->where('r.type = :type')
            ->setParameter('type', $type);

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }
}

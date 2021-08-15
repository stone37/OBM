<?php

namespace App\Repository;

use App\Entity\Command;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $CommandBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $CommandBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    public function findOneById($id): ?Command
    {
        try {
            $qb = $this->createQueryBuilder('c')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->leftJoin('c.items', 'items')
                ->leftJoin('c.user', 'user')
                ->leftJoin('c.advert', 'advert')
                ->addSelect('items')
                ->addSelect('user')
                ->addSelect('advert')
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }

        return $qb;
    }

    /**
     * @param User|UserInterface $user
     * @return mixed
     */
    public function byFacture(User $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->andWhere('c.validated = 1')
            ->andWhere('c.reference != 0')
            ->leftJoin('c.user', 'user')
            ->leftJoin('c.advert', 'advert')
            ->leftJoin('c.produits', 'produits')
            ->addSelect('user')
            ->addSelect('advert')
            ->addSelect('produits')
            ->orderBy('c.createdAt', 'desc')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }

    public function getUserOrderActiveNumber(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->andWhere('c.validated = 1')
            ->setParameter('user', $user);

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getUserOrderNumber(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :user')
            ->andWhere('c.validated = 0')
            ->setParameter('user', $user);

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getUserOrderActive(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->addSelect('user')
            ->where('c.validated = 1')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.createdAt', 'desc');

        return $qb;
    }

    public function getUserOrder(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.user', 'user')
            ->addSelect('user')
            ->where('c.validated = 0')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.createdAt', 'desc');

        return $qb;
    }

    public function getNumber()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.validated = 1');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }

    public function getLastOrders()
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.validated = 1')
            ->orderBy('c.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }
}

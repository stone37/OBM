<?php

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @return Payment[]
     */
    public function findFor(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.order', 'command')
            ->addSelect('command')
            ->where('command.user = :user')
            ->orderBy('p.createdAt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @param User|UserInterface $user
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findForId(int $id, User $user)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.order', 'command')
            ->addSelect('command')
            ->where('command.user = :user')
            ->andWhere('p.id = :id')
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getMonthlyRevenues(): array
    {
        return $this->aggregateRevenus('%Y-%m', '%m', 24);
    }

    public function getDailyRevenues(): array
    {
        return $this->aggregateRevenus('%Y-%m-%d', '%d', 30);
    }

    private function aggregateRevenus(string $group, string $label, int $limit): array
    {
        return array_reverse($this->createQueryBuilder('p')
            ->select(
                "DATE_FORMAT(p.createdAt, '$label') as date",
                "DATE_FORMAT(p.createdAt, '$group') as fulldate",
                'ROUND(SUM(p.price)) as amount'
            )
            ->groupBy('fulldate', 'date')
            ->orderBy('fulldate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult());
    }

    public function getMonthlyReport(int $year): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                'p.method as method',
                'EXTRACT(MONTH FROM p.createdAt) as month',
                'ROUND(SUM(p.price) * 100) / 100 as price',
                'ROUND(SUM(p.tax) * 100) / 100 as tax',
                'ROUND(SUM(p.discount) * 100) / 100 as fee'
            )
            ->groupBy('month', 'p.method')
            ->andWhere('EXTRACT(YEAR FROM p.createdAt) = :year')
            ->setParameter('year', $year)
            ->orderBy('month', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getLasts()
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function getNumber()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('count(p.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }
}

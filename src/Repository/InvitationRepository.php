<?php

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    /**
     * @param User $user
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findOneByUser(User $user)
    {
        $qb = $this->createQueryBuilder('i')
                    ->where('i.owner = :user')
                    ->setParameter('user', $user);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $code
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function findOneByCode(string $code)
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.code = :code')
            ->setParameter('code', $code);

        return $qb->getQuery()->getOneOrNullResult();
    }
}

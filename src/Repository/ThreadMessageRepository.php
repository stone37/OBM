<?php

namespace App\Repository;

use App\Entity\ThreadMessage;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThreadMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadMessage[]    findAll()
 * @method ThreadMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThreadMessage::class);
    }

    public function getNbUnreadMessageByParticipant(User $user)
    {
        $qb = $this->createQueryBuilder('tm')
            ->select('count(tm.id)')
            ->innerJoin('tm.metadata', 'mm')
            ->innerJoin('mm.participant', 'p')
            ->andWhere('tm.sender != :sender')
            ->setParameter('sender', $user->getId())
            ->andWhere('mm.isRead = 0');

        return $qb->getQuery()->getSingleScalarResult();
    }


}

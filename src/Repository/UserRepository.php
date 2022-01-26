<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\Admin\UserSearch;
use DateTime;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends AbstractRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail(string $email): object
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Requête permettant de récupérer un utilisateur pour le login.
     *
     * @throws NonUniqueResultException
     */
    public function findForAuth(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('LOWER(u.email) = :username')
            ->orWhere('LOWER(u.username) = :username')
            ->setMaxResults(1)
            ->setParameter('username', mb_strtolower($username))
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Cherche un utilisateur pour l'oauth.
     *
     * @throws NonUniqueResultException
     */
    public function findForOauth(string $service, ?string $serviceId, ?string $email): ?User
    {
        if (null === $serviceId || null === $email) {
            return null;
        }

        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->orWhere("u.{$service}Id = :serviceId")
            ->setMaxResults(1)
            ->setParameters([
                'email' => $email,
                'serviceId' => $serviceId,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Supprime les comptes utilisateurs retirée
     *
     * @return User[]
     */
    public function clean(): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.deleteAt IS NOT NULL')
            ->andWhere('u.deleteAt < NOW()');

        /** @var User[] $users */
        $users = $query->getQuery()->getResult();
        $query->delete(User::class, 'u')->getQuery()->execute();

        return $users;
    }

    public function getUsers()
    {
        $qb = $this->createQueryBuilder('u');

        return $qb;
    }

    /**
     * Requête permettant de récupérer un utilisateur par son ID
     *
     * @param $id
     * @return int|mixed|string|null
     */
    public function getUser($id)
    {
        try
        {
            $qb = $this->createQueryBuilder('u')
                ->where('u.id = :id')
                ->setParameter('id', (int)$id)
                ->leftJoin('u.adverts', 'adverts')
                ->addSelect('adverts')
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    public function refreshUser($id)
    {
        try
        {
            $qb = $this->createQueryBuilder('u')
                ->where('u.id = :id')
                ->setParameter('id', (int)$id)
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    /**
     * Requête permettant de récupérer le nombre total d'utilisateur
     *
     * @return QueryBuilder|int|mixed|string
     */
    public function getNumber()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    /**
     * Récupére les utilisateurs active
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getAdminUsers(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.city = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    /**
     * Récupére les utilisateurs non confirmé
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getUserNoConfirmed(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NOT NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.city = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    /**
     * Récupére les utilisateurs retirée
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getUserDeleted(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.deleteAt IS NOT NULL')
            ->andWhere('u.confirmationToken IS NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.city = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    /**
     * Récupére les utilisateurs premium
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getAdminUserPros(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NULL')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->andWhere('u.premiumEnd > :date')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->setParameter('date', new DateTime())
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->getCity())
            $qb->andWhere('u.city = :city')->setParameter('city', $search->getCity());

        return $qb;
    }

    /**
     * Récupére les compte admin
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getAdmins(UserSearch $search): ?QueryBuilder
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleteAt'))
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%'."ROLE_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        if ($search->getEmail())
            $qb->andWhere('u.email LIKE :email')->setParameter('email', '%'.$search->getEmail().'%');

        if ($search->getPhone())
            $qb->andWhere('u.phone LIKE :phone')->setParameter('phone', '%'.$search->getPhone().'%');

        if ($search->isEnabled())
            $qb->andWhere('u.deleteAt IS NULL')->andWhere('u.confirmationToken IS NULL');

        return $qb;
    }

    /**
     * Récupére les utilisateurs retirée
     *
     * @param UserSearch $search
     * @return QueryBuilder|null
     */
    public function getDeleted()
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.deleteAt IS NOT NULL')
            ->andWhere('u.deleteAt < NOW()');

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupére les mails des annonceurs
     *
     * @return int|mixed|string
     */
    public function findAdEmails()
    {
        $qb = $this->createQueryBuilder('u')
                    ->leftJoin('u.adverts', 'adverts')
                    ->select('u.email');

        $qb->where('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->andWhere('adverts IS NOT NULL')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupére les mails des utilisateurs
     *
     * @return int|mixed|string
     */
    public function findEmails()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email');

        $qb->where($qb->expr()->isNull('u.deleteAt'))
            ->andWhere('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NOT NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupére les mails des utilisateurs premium
     *
     * @return int|mixed|string
     */
    public function findProEmails()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('u.email');

        $qb->where($qb->expr()->isNull('u.deleteAt'))
            ->andWhere('u.deleteAt IS NULL')
            ->andWhere('u.confirmationToken IS NOT NULL')
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->andWhere('u.premiumEnd > :date')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->setParameter('date', new DateTime())
            ->orderBy('u.createdAt', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupére les derniers comptes utilisateurs active
     *
     * @return int|mixed|string
     */
    public function getLastClients()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->isNull('u.deleteAt'))
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%')
            ->orderBy('u.createdAt', 'desc')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    /**
     * Récupére le nombres total d'utilisateurs active
     * @return QueryBuilder|int|mixed|string
     */
    public function getUserNumber()
    {
        $qb = $this->createQueryBuilder('u')
            ->select('count(u.id)');

        $qb->where($qb->expr()->isNull('u.deleteAt'))
            ->andWhere('u.roles LIKE :roles')
            ->andWhere('u.roles NOT LIKE :roleA')
            ->andWhere('u.roles NOT LIKE :roleSA')
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {
        }

        return $qb;
    }
}

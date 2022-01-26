<?php

namespace App\Api\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

/**
 * Filtre les utilisateurs à renvoyer par l'API.
 */
final class UserQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {

        if (User::class !== $resourceClass) {
            return;
        }

        $rootAlias = $qb->getRootAliases()[0];

        $qb->where(sprintf('%s.deleteAt IS NULL', $rootAlias))
            ->andWhere(sprintf('%s.confirmationToken IS NULL', $rootAlias))
            ->andWhere(sprintf('%s.roles LIKE :roles', $rootAlias))
            ->andWhere(sprintf('%s.roles NOT LIKE :roleA', $rootAlias))
            ->andWhere(sprintf('%s.roles NOT LIKE :roleSA', $rootAlias))
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%');
    }

    /**
     * @param QueryBuilder $qb
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param array $identifiers
     * @param string|null $operationName
     * @param array $context
     */
    public function applyToItem(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        string $operationName = null,
        array $context = [])
    {
        if (User::class !== $resourceClass) {
            return;
        }

        $rootAlias = $qb->getRootAliases()[0];

        $qb->where(sprintf('%s.deleteAt IS NULL', $rootAlias))
            ->andWhere(sprintf('%s.confirmationToken IS NULL', $rootAlias))
            ->andWhere(sprintf('%s.roles LIKE :roles', $rootAlias))
            ->andWhere(sprintf('%s.roles NOT LIKE :roleA', $rootAlias))
            ->andWhere(sprintf('%s.roles NOT LIKE :roleSA', $rootAlias))
            ->setParameter('roles', '%'."".'%')
            ->setParameter('roleA', '%'."ROLE_ADMIN".'%')
            ->setParameter('roleSA', '%'."ROLE_SUPER_ADMIN".'%');
    }
}



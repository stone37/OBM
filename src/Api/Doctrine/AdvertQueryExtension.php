<?php

namespace App\Api\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Advert;
use DateTime;
use Doctrine\ORM\QueryBuilder;

/**
 * Filtre les annonces à renvoyer par l'API.
 */
final class AdvertQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ): void {

        if (Advert::class !== $resourceClass) {
            return;
        }

        $rootAlias = $qb->getRootAliases()[0];

        $qb->andWhere(sprintf('%s.validated = 1', $rootAlias))
            ->andWhere(sprintf('%s.denied = 0', $rootAlias))
            ->andWhere(sprintf('%s.deleted = 0', $rootAlias))
            ->andWhere(sprintf('%s.validatedAt >= :date', $rootAlias))
            ->setParameter('date', (new DateTime())->modify('-6 month'));
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
        if (Advert::class !== $resourceClass) {
            return;
        }

        $rootAlias = $qb->getRootAliases()[0];

        $qb->andWhere(sprintf('%s.validated = 1', $rootAlias))
            ->andWhere(sprintf('%s.denied = 0', $rootAlias))
            ->andWhere(sprintf('%s.deleted = 0', $rootAlias))
            ->andWhere(sprintf('%s.validatedAt >= :date', $rootAlias))
            ->setParameter('date', (new DateTime())->modify('-6 month'));
    }
}



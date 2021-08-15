<?php

namespace App\Repository;

use App\Entity\Advert;
use App\Entity\User;
use App\Model\Admin\AdvertSearch;
use App\Model\Search;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }

    /**
     * Recupere une annonce sa reference
     *
     * @param $reference
     * @return QueryBuilder|int|mixed|string|null
     */
    public function getAdvertByReference($reference)
    {
        $qb = $this->jointed()
                ->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.reference = :reference')
                ->setParameter('reference', $reference);

        try {
            $qb = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    /**
     * Recupere les annonces valide
     *
     * @return int|mixed|string
     */
    public function getAdvertValid()
    {
        $qb = $this->valide();

        return $qb->getQuery()->getResult();
    }

    /**
     * Supprime les annonces retirées
     *
     * @return Advert[]
     */
    public function clean(): array
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.deleted', true)
            ->andWhere('a.deleteAt < NOW()');

        /** @var Advert[] $adverts */
        $adverts = $query->getQuery()->getResult();
        $query->delete(Advert::class, 'a')->getQuery()->execute();

        return $adverts;
    }

    /**
     * Recherche les annonces validées
     *
     * @param Search $search
     * @return int|mixed|string
     */
    public function getValide(Search $search)
    {
        $qb = $this->jointed()
                ->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.validatedAt >= :date')
                ->andWhere('a.optionAdHeadEnd IS NULL')
                ->setParameter('date', (new DateTime())->modify('-6 month'));

        $qb = $this->searchWhereByDataFilter($qb, $search);
        $qb = $this->searchWhereByCategories($qb, $search);
        $qb = $this->searchWhereByDetail($qb, $search);
        $qb = $this->searchWhereByPrice($qb, $search);
        $qb = $this->searchWhereByType($qb, $search);
        $qb = $this->searchWhereByLocation($qb, $search);
        $qb = $this->searchGetUrgent($qb, $search);
        $qb = $this->searchByDataSort($qb, $search);

        return $qb->getQuery()->getResult();
    }

    private function searchGetUrgent(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getUrgent()) {
            $qb->andWhere('a.optionAdUrgentsEnd > NOW()');
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByDetail(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getMarque())
            $qb->andWhere('a.marque = :marque')->setParameter('marque', $search->getMarque());

        if ($search->getModel())
            $qb->andWhere('a.model = :model')->setParameter('model', $search->getModel());

        if ($search->getTypeCarburant())
            $qb->andWhere('a.typeCarburant = :typeCarburant')->setParameter('typeCarburant', $search->getTypeCarburant());

        if ($search->getKiloMin())
            $qb->andWhere('a.kilo >= :kiloMin')->setParameter('kiloMin', (int)$search->getkiloMin());

        if ($search->getKiloMax())
            $qb->andWhere('a.kilo <= :kiloMax')->setParameter('kiloMax', (int)$search->getKiloMax());

        if ($search->getAutoYearMin())
            $qb->andWhere('a.autoYear >= :autoYearMin')->setParameter('autoYearMin', (int)$search->getAutoYearMin());

        if ($search->getAutoYearMax())
            $qb->andWhere('a.autoYear <= :autoYearMax')->setParameter('autoYearMax', (int)$search->getAutoYearMax());

        if ($search->getUrgent())
            $qb->andWhere($qb->expr()->isNotNull('a.optionAdUrgentsEnd'));

        if ($search->getSurfaceMin())
            $qb->andWhere('a.surface >= :surfaceMin')->setParameter('surfaceMin', (int)$search->getSurfaceMin());

        if ($search->getSurfaceMax())
            $qb->andWhere('a.surface <= :surfaceMax')->setParameter('surfaceMax', (int)$search->getSurfaceMax());

        if ($search->getImmobilierState())
            $qb->andWhere('a.immobilierState = :immobilierState')->setParameter('immobilierState', $search->getImmobilierState());

        if ($search->getNbrePiece())
            $qb->andWhere('a.nombrePiece = :nombrePiece')->setParameter('nombrePiece', $search->getNbrePiece());

        if ($search->getNbreChambre())
            $qb->andWhere('a.nombreChambre = :nombreChambre')->setParameter('nombreChambre', $search->getNbreChambre());

        if ($search->getNbreSalleBain())
            $qb->andWhere('a.nombreSalleBain = :nombreSalleBain')->setParameter('nombreSalleBain', $search->getNbreSalleBain());

        if ($search->getImmobilierAcces())
            $qb->andWhere('a.access LIKE :access')->setParameter('access', '%'.$search->getImmobilierAcces().'%');

        if ($search->getProximite())
            $qb->andWhere('a.proximite LIKE :proximite')->setParameter('proximite', '%'.$search->getProximite().'%');

        if ($search->getInterior())
            $qb->andWhere('a.interior LIKE :interior')->setParameter('interior', '%'.$search->getInterior().'%');

        if ($search->getExterior())
            $qb->andWhere('a.exterior LIKE :exterior')->setParameter('exterior', '%'.$search->getExterior().'%');

        if ($search->getState())
            $qb->andWhere('a.state = :state')->setParameter('state', $search->getState());

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByCategories(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getCategory()) {
            $qb->andWhere('category.slug = :category_slug')->setParameter('category_slug', $search->getCategory());
        }

        if ($search->getSubCategory()) {
            $qb->andWhere('subCategory.slug = :sub_category_slug')->setParameter('sub_category_slug', $search->getSubCategory());
        }

        if ($search->getSubDivision()) {
            $qb->andWhere('subDivision.slug = :sub_division_slug')->setParameter('sub_division_slug', $search->getSubDivision());
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByPrice(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getPriceMin()) {
            $qb->andWhere('a.price >= :priceMin')->setParameter('priceMin', (int)$search->getPriceMin());
        }

        if ($search->getPriceMax()) {
            $qb->andWhere('a.price <= :priceMax')->setParameter('priceMax', (int)$search->getPriceMax());
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByLocation(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getCity()) {
            $qb->andWhere('location.name = :city')->setParameter('city', $search->getCity());
        }

        if ($search->getZone()) {
            $qb->andWhere('location.detail LIKE :detail')->setParameter('detail', '%'.$search->getZone().'%');
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByType(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getType()) {
            $qb->andWhere('a.type = :type')->setParameter('type', $search->getType());
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchWhereByDataFilter(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getData()) {
            $qb->andWhere('a.title LIKE :key')
                ->orWhere('a.description LIKE :key')
                ->setParameter('key', '%'.$search->getData().'%');
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Search $search
     * @return QueryBuilder
     */
    private function searchByDataSort(QueryBuilder $qb, Search $search): QueryBuilder
    {
        if ($search->getOrder()) {
            if ($search->getOrder() == 'priceAsc')
                $qb->orderBy('a.price', 'asc');
            elseif ($search->getOrder() == 'priceDesc')
                $qb->orderBy('a.price', 'desc');
            elseif ($search->getOrder() == 'yearAsc')
                $qb->orderBy('a.autoYear', 'asc');
            elseif ($search->getOrder() == 'yearDesc')
                $qb->orderBy('a.autoYear', 'desc');
            elseif ($search->getOrder() == 'publishedAsc')
                $qb->orderBy('a.validatedAt', 'asc');
            elseif ($search->getOrder() == 'publishedDesc')
                $qb->orderBy('a.validatedAt', 'desc');
            else
                $qb->orderBy('a.position', 'desc');
        } else {
            $qb->orderBy('a.position', 'desc');
        }

        return $qb;
    }

    /**
     * Recupere les annonces similaire d'une annonce
     *
     * @param Advert $advert
     * @return int|mixed|string
     */
    public function getSimilar(Advert $advert)
    {
        $qb = $this->jointed()
                ->where('a.validated = 1')
                ->andWhere('a.id <> :id')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.validatedAt >= :date')
                ->andWhere('a.category = :category')
                ->andWhere('a.subCategory = :subCategory')
                ->andWhere('location.name = :city')
                ->andWhere('a.type = :type')
                ->setParameter('id', $advert->getId())
                ->setParameter('date', (new DateTime())->modify('-6 month'))
                ->setParameter('category', $advert->getCategory())
                ->setParameter('subCategory', $advert->getSubCategory())
                ->setParameter('city', $advert->getLocation()->getName())
                ->setParameter('type', $advert->getType());

        if ($advert->getSubDivision()) {
            $qb->andWhere('a.subDivision = :subDivision')->setParameter('subDivision', $advert->getSubDivision());
        }

        $qb->orderBy('a.validatedAt', 'desc')->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere les annonces active d'un utilisateur
     *
     * @param Search $search
     * @return mixed
     */
    public function getUserAdvertActive(Search $search)
    {
        $qb = $this->jointed()
                ->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.user = :user')
                ->setParameter('user', $search->getUser());

        $qb->andWhere('a.validatedAt >= :date')
            ->setParameter('date', (new DateTime())->modify('-6 month'));

        if ($search->getTitle()) {
            $qb->andWhere('a.title LIKE :title')
                ->setParameter('title', '%'.$search->getTitle().'%');
        }

        $qb = $this->searchByDataSort($qb, $search);

        return $qb;
    }

    /**
     * Recupere les annonces d'un utilisateur
     *
     * @param Search $search
     * @return mixed
     */
    public function getUserAdvert(Search $search)
    {
        $qb = $this->jointed()
                ->where('a.validated = 0')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.user = :user')
                ->setParameter('user', $search->getUser());

        if ($search->getTitle()) {
            $qb->andWhere('a.title LIKE :title')
                ->setParameter('title', '%'.$search->getTitle().'%');
        }

        return $qb;
    }

    /**
     * Recupere le nombre d'annonces active d'un utilisateur
     *
     * @param User $user
     * @return QueryBuilder|mixed
     */
    public function getUserAdvertActiveNumber(Search $search)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.validated = 1')
            ->andWhere('a.denied = 0')
            ->andWhere('a.deleted = 0')
            ->setParameter('user', $search->getUser());

        $qb->andWhere('a.validatedAt >= :date')
            ->setParameter('date', (new DateTime())->modify('-6 month'));

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }


    /**
     * Recupere le nombre d'annonces active d'un utilisateur
     *
     * @param User $user
     * @return QueryBuilder|mixed
     */
    public function userAdvertActiveNumber(User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.validated = 1')
            ->andWhere('a.denied = 0')
            ->andWhere('a.deleted = 0')
            ->setParameter('user', $user);

        $qb->andWhere('a.validatedAt >= :date')
            ->setParameter('date', (new DateTime())->modify('-6 month'));

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }


    /**
     * Recupere le nombre d'annonces d'un utilisateur
     *
     * @param User $user
     * @return QueryBuilder|mixed
     */
    public function getUserAdvertNumber(Search $search)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)')
            ->where('a.user = :user')
            ->andWhere('a.validated = 0')
            ->andWhere('a.denied = 0')
            ->andWhere('a.deleted = 0')
            ->setParameter('user', $search->getUser());

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    const AD_NV = 1;
    const AD_V = 2;
    const AD_D = 3;
    const AD_DTD = 4;

    /**
     * Recupere les annpnces dans l'administration
     *
     * @param AdvertSearch $search
     * @param $type
     * @return QueryBuilder
     */
    public function getAdmin(AdvertSearch $search, $type)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->leftJoin('a.location', 'location')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->addSelect('location');

        if ($type == self::AD_NV) {

            $qb->where('a.validated = 0')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->orderBy('a.createdAt', 'desc');

        } elseif ($type == self::AD_V) {

            $qb->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0');

            $date = new DateTime();
            $date->modify('-6 month');

            $qb->andWhere('a.validatedAt >= :date')
                ->setParameter('date', $date)
                ->orderBy('a.validatedAt', 'desc');

        } elseif ($type == self::AD_D) {

            $qb->where('a.validated = 0')
                ->andWhere('a.denied = 1')
                ->andWhere('a.deleted = 0')
                ->orderBy('a.deniedAt', 'desc');

        } elseif ($type == self::AD_DTD) {

            $qb->andWhere('a.deleted = 1')
                ->orderBy('a.deletedAt', 'desc');

        } else {
            $qb->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0');

            $date = new DateTime();
            $date->modify('-6 month');

            $qb->andWhere('a.validatedAt <= :date')
                ->setParameter('date', $date)
                ->orderBy('a.validatedAt', 'desc');
        }

        if ($search->getType())
            $qb->andWhere('a.type = :type')->setParameter('type', $search->getType());

        if ($search->getReference())
            $qb->andWhere('a.reference = :reference')->setParameter('reference', $search->getReference());

        if ($search->getCity())
            $qb->andWhere('location.name = :city')->setParameter('city', $search->getCity());

        if ($search->getCategory())
            $qb->andWhere('category.id = :category_id')->setParameter('category_id', (int)$search->getCategory());

        if ($search->getSubCategory())
            $qb->andWhere('subCategory.id = :sub_category_id')->setParameter('sub_category_id', (int)$search->getSubCategory());

        if ($search->getSubDivision())
            $qb->andWhere('subDivision.id = :sub_division_id')->setParameter('sub_division_id', (int)$search->getSubDivision());

        return $qb;
    }

    /**
     * Recherche les annonces dans l'adminitration
     *
     * @param AdvertSearch $search
     * @param User $user
     * @return QueryBuilder
     */
    public function getUserAdmin(AdvertSearch $search, User $user)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->leftJoin('a.location', 'location')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->addSelect('location')
            ->where('a.user = :user')
            ->setParameter('user', $user);

        if ($search->getType())
            $qb->andWhere('a.type = :type')->setParameter('type', $search->getType());

        if ($search->getReference())
            $qb->andWhere('a.reference = :reference')->setParameter('reference', $search->getReference());

        if ($search->getCity())
            $qb->andWhere('location.name = :city')->setParameter('city', $search->getCity());

        if ($search->getCategory())
            $qb->andWhere('category.id = :category_id')->setParameter('category_id', (int)$search->getCategory());

        if ($search->getSubCategory())
            $qb->andWhere('subCategory.id = :sub_category_id')->setParameter('sub_category_id', (int)$search->getSubCategory());

        if ($search->getSubDivision())
            $qb->andWhere('subDivision.id = :sub_division_id')->setParameter('sub_division_id', (int)$search->getSubDivision());

        return $qb;
    }

    /**
     * Supprime les annonces d'un utilisateur
     *
     * @param User $user
     */
    public function deleteForUser(User $user): void
    {
        $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * Recherche des annonces
     *
     * @param $query
     * @return int|mixed|string
     */
    public function search($query)
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->leftJoin('a.location', 'location')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->addSelect('location')
            ->groupBy('a.subCategory');

        $qb->where('a.title LIKE :key')
            ->orWhere('a.marque LIKE :key')
            ->orWhere('a.description LIKE :key')
            ->orWhere('subCategory.name LIKE :key')
            ->orWhere('subDivision.name LIKE :key')
            ->andwhere('a.validated = 1')
            ->andWhere('a.denied = 0')
            ->andWhere('a.deleted = 0')
            ->andWhere('a.validatedAt >= :date')
            ->setParameter('key', '%'.$query.'%')
            ->setParameter('date', (new DateTime())->modify('-6 month'))
            ->orderBy('a.position', 'desc')
            ->setMaxResults(8);

        return $qb->getQuery()->getResult();
    }

    /**
     * Nombre total d'annonce validées
     *
     * @return QueryBuilder|int|mixed|string
     */
    public function getValideNumber()
    {
        $qb = $this->valide()->select('count(a.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    /**
     * Nombre total d'annonce non validées
     *
     * @return QueryBuilder|int|mixed|string
     */
    public function getNotValideNumber()
    {
        $qb = $this->notValide()->select('count(a.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    /**
     * Nombre total d'annonce expirées
     *
     * @return QueryBuilder|int|mixed|string
     */
    public function getExpiredNumber()
    {
        $qb = $this->expired()->select('count(a.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    /**
     * Nombre total d'annonce retirées
     *
     * @return QueryBuilder|int|mixed|string
     */
    public function getDeletedNumber()
    {
        $qb = $this->delete()->select('count(a.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        } catch (NonUniqueResultException $exception) {} catch (NoResultException $e) {}

        return $qb;
    }

    public function getDenied()
    {
        $qb = $this->denied();

        return $qb->getQuery()->getResult();
    }

    public function getExpired()
    {
        $qb = $this->expired();

        return $qb->getQuery()->getResult();
    }

    public function getDeleted()
    {
        $qb = $this->delete();

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere les annonce la galerie page d'accueil
     *
     * @return array|null
     */
    public function getGallery(): ?array
    {
        $qb = ($this->valide())
                ->andWhere('a.optionAdGalleryEnd > NOW()')
                ->orderBy('a.optionAdGalleryEnd', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere les annonce en tete de liste
     *
     * @return array|null
     */
    public function getHeader($category_slug, $sub_category_slug = null, $sub_division_slug = null): ?array
    {
        $qb = ($this->valide())
            ->andWhere('a.optionAdHeadEnd > NOW()')
            ->andWhere('category.slug = :category')->setParameter('category', $category_slug)
            ->orderBy('a.optionAdHeadEnd', 'desc');

        if ($sub_category_slug) {
            $qb->andWhere('subCategory.slug = :subCategory')->setParameter('subCategory', $sub_category_slug);
        }

        if ($sub_division_slug) {
            $qb->andWhere('subDivision.slug = :subDivision')->setParameter('subDivision', $sub_division_slug);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere les annonce en vedette
     *
     * @return array|null
     */
    public function getVedette($category_slug, $sub_category_slug = null, $sub_division_slug = null): ?array
    {
        $qb = ($this->valide())
                ->andWhere('a.optionAdVedetteEnd > NOW()')
                ->andWhere('category.slug = :category')->setParameter('category', $category_slug)
                ->orderBy('a.optionAdVedetteEnd', 'desc');

        if ($sub_category_slug) {
            $qb->andWhere('subCategory.slug = :subCategory')->setParameter('subCategory', $sub_category_slug);
        }

        if ($sub_division_slug) {
            $qb->andWhere('subDivision.slug = :subDivision')->setParameter('subDivision', $sub_division_slug);
        }

        return $qb->getQuery()->getResult();
    }

    private function valide(): QueryBuilder
    {
        $date = (new DateTime())->modify('-6 month');

        $qb = $this->createQueryBuilder('a')
                ->leftJoin('a.category', 'category')
                ->leftJoin('a.subCategory', 'subCategory')
                ->leftJoin('a.subDivision', 'subDivision')
                ->leftJoin('a.location', 'location')
                ->leftJoin('a.images', 'images')
                ->addSelect('category')
                ->addSelect('subCategory')
                ->addSelect('subDivision')
                ->addSelect('location')
                ->addSelect('images')
                ->where('a.validated = 1')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0')
                ->andWhere('a.validatedAt >= :date')
                ->setParameter('date', $date);

        return $qb;
    }

    private function denied(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.validated = 0')
            ->andWhere('a.denied = 1')
            ->andWhere('a.deleted = 0');

        return $qb;
    }

    private function expired(): QueryBuilder
    {
        $date = (new DateTime())->modify('-6 month');

        $qb = $this->createQueryBuilder('a')
            ->where('a.validated = 1')
            ->andWhere('a.denied = 0')
            ->andWhere('a.deleted = 0')
            ->andWhere('a.validatedAt <= :date')
            ->setParameter('date', $date);

        return $qb;
    }

    private function delete(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
                    ->andWhere('a.deleted = 1');

        return $qb;
    }

    private function notValide(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
                ->where('a.validated = 0')
                ->andWhere('a.denied = 0')
                ->andWhere('a.deleted = 0');

        return $qb;
    }

    private function jointed(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'category')
            ->leftJoin('a.subCategory', 'subCategory')
            ->leftJoin('a.subDivision', 'subDivision')
            ->leftJoin('a.location', 'location')
            ->leftJoin('a.images', 'images')
            ->leftJoin('a.user', 'user')
            ->leftJoin('a.favorites', 'favorites')
            ->leftJoin('a.reads', 'reads')
            ->addSelect('category')
            ->addSelect('subCategory')
            ->addSelect('subDivision')
            ->addSelect('location')
            ->addSelect('images')
            ->addSelect('user')
            ->addSelect('favorites')
            ->addSelect('reads');

        return $qb;
    }
}

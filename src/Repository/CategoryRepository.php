<?php

namespace App\Repository;

use App\Entity\Category;
use App\Model\Admin\CategorySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Recupere les categories par son parent
     *
     * @param null $parent
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getWithParent($parent = null)
    {
        $qb = $this->createQueryBuilder('c');

        if (!$parent)
            $qb->where($qb->expr()->isNull('c.parent'));
        else
            $qb->where('c.parent = :parent')->setParameter('parent', $parent);

        $qb->andWhere('c.enabled = 1')
            ->orderBy('c.position', 'asc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere les categories par son parent
     *
     * @param $slug
     * @return array
     */
    public function getWithParentSlug($slug)
    {
        $results = $this->createQueryBuilder('c')
                ->leftJoin('c.parent', 'parent')
                ->addSelect('parent')
                ->where('parent.slug = :slug')
                ->andWhere('c.enabled = 1')
                ->setParameter('slug', $slug)
                ->orderBy('c.position', 'asc')
                ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['slug'];

        return $data;
    }

    /**
     * Recupere les categories qui n'ont pas de parent
     *
     * @return int|mixed|string
     */
    public function getWithParentNull()
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where($qb->expr()->isNull('c.parent'))
            ->andWhere('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->leftJoin('children.children', 'c_children')
            ->addSelect('children')
            ->addSelect('c_children')
            ->orderBy('c.position', 'asc');

        return $qb->getQuery()->getResult();
    }

    /**
     * Recupere une categorie par son slug
     *
     * @param $slug
     * @return int|mixed|string|null
     */
    public function findBySlug($slug)
    {
        try
        {
            $qb = $this->createQueryBuilder('c')
                ->where('c.enabled = 1')
                ->andWhere('c.slug = :slug')
                ->leftJoin('c.children', 'children')
                ->leftJoin('c.parent', 'parent')
                ->addSelect('children')
                ->addSelect('parent')
                ->setParameter('slug', (string)$slug)
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {}

        return $qb;
    }

    /**
     * Recupere le nombre total de categories
     * @return QueryBuilder|int|mixed|string
     */
    public function getNumber()
    {
        $qb = $this->createQueryBuilder('c')
            ->select('count(c.id)');

        try {
            $qb = $qb->getQuery()->getSingleScalarResult();
        }  catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }

        return $qb;
    }

    /**
     * Recupere des categories par leur parent
     *
     * @param CategorySearch $search
     * @param null $parent
     * @return QueryBuilder
     */
    public function getAdminWithParent(CategorySearch $search, $parent = null)
    {
        $qb = $this->createQueryBuilder('c');

        if (!$parent)
            $qb->where($qb->expr()->isNull('c.parent'));
        else
            $qb->where('c.parent = :parent')->setParameter('parent', $parent);

        $qb->orderBy('c.position', 'asc');

        if ($search->isEnabled())
            $qb->andWhere('c.enabled = 1');

        if ($search->getName())
            $qb->andWhere('c.name LIKE :name')->setParameter('name', '%'.$search->getName().'%');


        return $qb;
    }

    /**
     * Recupere les categories qui un parent
     * @return QueryBuilder
     */
    public function getCategoriesWithParentNotNull()
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where($qb->expr()->isNotNull('c.parent'))
            ->andWhere('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->addSelect('children')
            ->orderBy('c.parent', 'asc');

        return $qb;
    }

    /**
     * Recupere les categories actif
     *
     * @return QueryBuilder
     */
    public function getEnabledQueryBuilder()
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->addSelect('children')
            ->orderBy('c.parent', 'asc');

        return $qb;
    }

    /**
     * Recupere les categories qui n'ont de pas de parent
     *
     * @return array
     */
    public function getAdminCategoryParentNull()
    {
        $qb = $this->createQueryBuilder('c');

        $results = $qb->where($qb->expr()->isNull('c.parent'))
                    ->andWhere('c.enabled = 1')
                    ->orderBy('c.position', 'asc')
                    ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['id'];

        return $data;
    }

    public function getWithParentNullData()
    {
        $qb = $this->createQueryBuilder('c');

        $results = $qb->where($qb->expr()->isNull('c.parent'))
            ->andWhere('c.enabled = 1')
            ->orderBy('c.position', 'asc')
            ->getQuery()->getArrayResult();

        $data = [];

        foreach ($results as $result)
            $data[$result['name']] = $result['slug'];

        return $data;
    }

    /**
     * Recupere les categories qui n'ont de pas de parent
     *
     * @return QueryBuilder
     */
    public function adminQueryBuilderParentNull(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c');

        $qb->where($qb->expr()->isNull('c.parent'))
            ->andWhere('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->addSelect('children')
            ->orderBy('c.position', 'asc');

        return $qb;
    }

    public function findForApi(): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where($qb->expr()->isNull('c.parent'))
            ->andWhere('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->addSelect('children')
            ->orderBy('c.position', 'asc')
            ->getQuery()
            ->getResult();
    }

    public function findPartial(int $id): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->andWhere('c.enabled = 1')
            ->leftJoin('c.children', 'children')
            ->addSelect('children')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

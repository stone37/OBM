<?php

namespace App\Manager;

use App\Entity\Alert;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class AlertManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Créer une entité alerte
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Alert
     */
    public function createAlert(Category $category, Category $subCategory = null, Category $subDivision = null): Alert
    {
        $alert = (new Alert())
                ->setCategory($category)
                ->setSubCategory($subCategory)
                ->setSubDivision($subDivision)
                ->setUser($this->security->getUser());

        return $alert;
    }

    /**
     * Créer une entité alerte
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Alert
     */
    public function createApiAlert(Alert $alert, Category $category, Category $subCategory = null, Category $subDivision = null): Alert
    {
        $alert->setCategory($category)
            ->setSubCategory($subCategory)
            ->setSubDivision($subDivision)
            ->setUser($this->security->getUser());

        return $alert;
    }

    /**
     * @param Category $category
     * @param Category|null $subCategory
     * @param Category|null $subDivision
     * @return bool
     */
    public function hasAlert(Category $category, Category $subCategory = null, Category $subDivision = null): bool
    {
        $alert = $this->em->getRepository(Alert::class)->findOneBy([
            'category' => $category,
            'subCategory' => $subCategory,
            'subDivision' => $subDivision,
            'user' => $this->security->getUser(),
        ]);

        return ($alert)? true : false;
    }

    /**
     * @param $slug
     * @return Category|null
     */
    public function find($slug): ?Category
    {
        return $this->em->getRepository(Category::class)->findBySlug($slug);
    }
}


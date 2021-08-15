<?php

namespace App\Service;

use App\Entity\Advert;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CategoryService
{
    private $request;
    private $em;

    public  function __construct(RequestStack $request, EntityManagerInterface $em)
    {
        $this->request = $request;
        $this->em = $em;
    }

    public function addCategories(Advert $advert)
    {
        $category_slug     = $this->request->getCurrentRequest()->attributes->get('category_slug');
        $sub_category_slug = $this->request->getCurrentRequest()->query->get('c');
        $sub_division_slug = $this->request->getCurrentRequest()->query->get('sc');

        if ($sub_division_slug) {
            $category = $this->getCategory($sub_division_slug);

            $advert->setSubDivision($category);
            $advert->setSubCategory($category->getParent());
            $advert->setCategory($category->getParent()->getParent());
        } elseif ($sub_category_slug) {
            $category = $this->getCategory($sub_category_slug);

            $advert->setSubCategory($category);
            $advert->setCategory($category->getParent());
        } else {
            $category =  $this->getCategory($category_slug);

            $advert->setCategory($category);
        }

        return $advert;
    }

    /**
     * Retour la catégorie principale
     *
     * @return Category
     */
    public function getCategoryPrincipale(): Category
    {
        $category_slug     = $this->request->getCurrentRequest()->attributes->get('category_slug');
        $sub_category_slug = $this->request->getCurrentRequest()->attributes->get('sub_category_slug');
        $sub_division_slug = $this->request->getCurrentRequest()->attributes->get('sub_division_slug');

        if ($sub_division_slug)
            $category = $this->getCategory($sub_division_slug);
        elseif ($sub_category_slug)
            $category = $this->getCategory($sub_category_slug);
        else
            $category =  $this->getCategory($category_slug);

        return $category;
    }

    private function getCategory($slug): ?Category
    {
        return $this->em->getRepository(Category::class)->findBySlug($slug);
    }
}
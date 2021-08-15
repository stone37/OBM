<?php

namespace App\Controller\Traits;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Trait ControllerTrait
 * @package App\Controller\Traits
 */
Trait ControllerTrait
{
    /**
     * @param EntityManagerInterface $manager
     * @param $id
     * @return User|null
     */
    public function getUsers(EntityManagerInterface $manager, $id): ?User
    {
        return $manager->getRepository(User::class)->getUser($id);
    }

    /**
     * @param EntityManagerInterface $manager
     * @return int|mixed|string
     */
    public function getCategories(EntityManagerInterface $manager)
    {
        return $manager->getRepository(Category::class)->getWithParentNull();
    }

    /**
     * @param Breadcrumbs $breadcrumbs
     * @return Breadcrumbs
     */
    public function breadcrumb(Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->addItem('Acceuil', $this->generateUrl('app_home'));

        return $breadcrumbs;
    }
}


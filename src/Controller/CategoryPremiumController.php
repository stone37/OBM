<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\CategoryPremium;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryPremiumController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function premium(EntityManagerInterface $em)
    {
        return $this->render('site/home/categoryPremium.html.twig', [
            'premiums' => $em->getRepository(CategoryPremium::class)->getCategoryPremiumEnabled()
        ]);
    }
}

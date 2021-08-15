<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Manager\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function megaNavBar(Request $request, EntityManagerInterface $em)
    {
        return $this->render('site/menu/megaNavbar.html.twig', [
            'settings' => $this->settings,
            'categories' => $this->getCategories($em),
            'request' => $request,
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sideNavBar(Request $request, EntityManagerInterface $em)
    {
        if ($this->getUser())
            $user = $this->getUsers($em, $this->getUser()->getId());
        else
            $user = null;

        return $this->render('site/menu/sideNavbar.html.twig', [
            'settings' => $this->settings,
            'categories' => $this->getCategories($em),
            'request' => $request,
            'user' => $user,
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dropdownMenu(EntityManagerInterface $em)
    {
        if ($this->getUser())
            $user = $this->getUsers($em, $this->getUser()->getId());
        else
            $user = null;

        return $this->render('site/menu/dropdown.html.twig', [
            'settings' => $this->settings,
            'categories' => $this->getCategories($em),
            'user' => $user,
        ]);
    }
}

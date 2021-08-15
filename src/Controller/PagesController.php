<?php

namespace App\Controller;

use App\Manager\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends AbstractController
{
    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    public function env(): Response
    {
        return $this->render('site/pages/env.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function confidentialite(): Response
    {
        return $this->render('site/pages/confidentialite.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function help(): Response
    {
        return $this->render('site/pages/help.html.twig');
    }

    public function mention(): Response
    {
        return $this->render('site/pages/mention.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function cgu(): Response
    {
        return $this->render('site/pages/cgu.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function charte(): Response
    {
        return $this->render('site/pages/charte.html.twig', [
            'settings' => $this->settings,
        ]);
    }
}

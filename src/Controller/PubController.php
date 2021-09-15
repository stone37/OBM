<?php

namespace App\Controller;

use App\Manager\PubManager;
use App\Service\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PubController extends AbstractController
{
    private $manager;
    private $settings;

    public function __construct(PubManager $pubManager, SettingsManager $manager)
    {
        $this->manager = $pubManager;
        $this->settings = $manager->get();
    }

    public function index($type)
    {
        $pubs = $this->manager->getHome($type);

        return $this->render('site/pub/index.html.twig', [
            'pubs' => $pubs,
            'settings' => $this->settings,
        ]);
    }

    public function listing(Request $request, $type)
    {
        $pubs = $this->manager->getListing($request, $type);

        return $this->render('site/pub/listing.html.twig', [
            'pubs' => $pubs,
            'settings' => $this->settings,
            'type' => $type
        ]);
    }
}



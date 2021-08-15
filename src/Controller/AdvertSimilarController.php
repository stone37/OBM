<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Settings;
use App\Manager\AdvertManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdvertSimilarController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param AdvertManager $manager
     * @param Advert $advert
     * @param Settings $settings
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AdvertManager $manager, Advert $advert, Settings $settings)
    {
        $similar = $manager->getAdvertSimilar($advert);

        return $this->render('site/advert/show/_similar.html.twig', [
            'reference' => $advert,
            'settings' => $settings,
            'adverts' => $similar
        ]);
    }
}


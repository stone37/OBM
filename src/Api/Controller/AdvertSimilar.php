<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Manager\AdvertManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertSimilar extends AbstractController
{
    private AdvertManager $manager;

    public function __construct(AdvertManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Advert $advert
     */
    public function __invoke(Advert $advert, $data)
    {
        return $this->manager->getAdvertSimilar($advert);
    }
}



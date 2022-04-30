<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Manager\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserThreadByAdvert extends AbstractController
{
    private ThreadManager $manager;

    public function __construct(ThreadManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Advert $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke($data, Advert $advert)
    {
       return $this->manager->findThreadsCreatedByAdvert($advert, $this->getUser());
    }
}



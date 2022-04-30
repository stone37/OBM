<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Manager\ThreadManager;
use App\Provider\ThreadProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CheckUserThreadByAdvert extends AbstractController
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
    public function __invoke($data)
    {
       return $this->json(['data' => (bool)($this->manager->nbThreadsCreatedByAdvert($data, $this->getUser()))], Response::HTTP_OK);
    }
}



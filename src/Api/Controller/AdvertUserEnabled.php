<?php

namespace App\Api\Controller;

use App\Entity\User;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertUserEnabled extends AbstractController
{
    private AdvertRepository $advertRepository;

    public function __construct(AdvertRepository $repository)
    {
        $this->advertRepository = $repository;
    }

    public function __invoke(User $user, $data)
    {
        $adverts = $this->advertRepository->apiUserAdvertActive($user);

        return $adverts;
    }
}



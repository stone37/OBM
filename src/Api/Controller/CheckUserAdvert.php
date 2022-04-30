<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAdvert extends AbstractController
{
    /**
     * @param Advert $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke($data)
    {
       return $this->json(['data' => $this->getUser()->getEmail() === $data->getUser()->getEmail()], Response::HTTP_OK);
    }
}



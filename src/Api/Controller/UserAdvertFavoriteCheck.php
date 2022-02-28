<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAdvertFavoriteCheck extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Advert $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke($data)
    {
        $favorite = $this->em->getRepository(Favorite::class)->findOneBy([
            'user' => $this->getUser(),
            'advert' => $data
        ]);

        return ($favorite) ?
            $this->json(['code' => Response::HTTP_OK, 'message' => true], Response::HTTP_OK) :
            $this->json(['code' => Response::HTTP_OK, 'message' => false], Response::HTTP_OK);
    }
}



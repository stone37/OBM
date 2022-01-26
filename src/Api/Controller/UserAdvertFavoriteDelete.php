<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAdvertFavoriteDelete extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Advert $data
     */
    public function __invoke($data)
    {
        $favorite = $this->em->getRepository(Favorite::class)->findOneBy([
            'user' => $this->getUser(),
            'advert' => $data
        ]);

        if (!$favorite) {
            $this->json(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Ressource indisponible'], Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($favorite);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}



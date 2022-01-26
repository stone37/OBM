<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\Favorite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAdvertFavoriteCreate extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Advert $advert
     * @param Favorite $data
     */
    public function __invoke(Advert $advert, $data)
    {
        $favorite = $this->em->getRepository(Favorite::class)
                            ->findOneBy(['advert' => $advert, 'user' => $this->getUser()]);

        if ($favorite) {
            return $this->json('Cette annonce est déjà dans les favoris de l\'utilisateur', Response::HTTP_NOT_FOUND);
        }

        $data->setAdvert($advert)->setUser($this->getUser());

        return $data;
    }
}



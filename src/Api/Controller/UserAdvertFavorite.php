<?php

namespace App\Api\Controller;


use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserAdvertFavorite extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(User $user, $data)
    {
        $favorites = $this->em->getRepository(Favorite::class)->getByUser($user);

        return $favorites;
    }
}



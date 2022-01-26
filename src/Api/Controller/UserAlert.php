<?php

namespace App\Api\Controller;

use App\Entity\Alert;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAlert extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(User $user, $data)
    {
        $alerts = $this->em->getRepository(Alert::class)->getByUser($user);

        return $alerts;
    }
}



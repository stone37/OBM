<?php

namespace App\Api\Controller;

use App\Entity\Alert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAlertDelete extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Alert $data
     */
    public function __invoke($data)
    {
        $this->em->remove($data);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}



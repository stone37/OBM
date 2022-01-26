<?php

namespace App\Api\Controller;

use App\Entity\Alert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAlertStatus extends AbstractController
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
        if ($data->isEnabled()) {
            $data->setEnabled(false);
            $this->em->flush();

            return $this->json(['code' => Response::HTTP_OK, 'message' => 'Votre alerte est maintenant inactive'], Response::HTTP_OK);
        } else {
            $data->setEnabled(true);
            $this->em->flush();

            return $this->json(['code' => Response::HTTP_OK, 'message' => 'Votre alerte est maintenant active'], Response::HTTP_OK);
        }
    }
}



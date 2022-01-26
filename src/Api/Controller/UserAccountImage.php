<?php

namespace App\Api\Controller;

use App\Entity\User;
use DateTime;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserAccountImage extends AbstractController
{
    /**
     * @param User $data
     * @param Request $request
     */
    public function __invoke($data, Request $request)
    {
        if (!($data instanceof User)) {
            throw new RuntimeException("Utilisateur attendu");
        }

        $data->setFile($request->files->get('file'));
        $data->setUpdatedAt(new DateTime());

        return $data;
    }
}



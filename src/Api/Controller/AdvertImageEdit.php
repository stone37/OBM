<?php

namespace App\Api\Controller;

use App\Entity\AdvertPicture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;;
use Symfony\Component\HttpFoundation\Request;


class AdvertImageEdit extends AbstractController
{
    /**
     * @param AdvertPicture $data
     * @param Request $request
     */
    public function __invoke($data, Request $request)
    {
        $files = $request->files;

        foreach ($files as $file) {
            $data->setFile($file);
        }

        return $data;
    }
}



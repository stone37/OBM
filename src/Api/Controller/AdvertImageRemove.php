<?php

namespace App\Api\Controller;

use App\Entity\AdvertPicture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertImageRemove extends AbstractController
{
    /**
     * @param AdvertPicture $data
     */
    public function __invoke($data)
    {
        $data->setAdvert(null);

        return $data;
    }
}



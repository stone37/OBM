<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertDelete extends AbstractController
{
    /**
     * @param Advert $data
     */
    public function __invoke($data)
    {
        $data->setDeleted(true);
        $data->setDeletedAt(new DateTime());

        return $data;
    }
}



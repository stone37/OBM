<?php

namespace App\Event;

use App\Entity\Advert;
use Symfony\Component\HttpFoundation\Request;

class AdvertCreatedInitializeEvent
{
    /**
     * @var Advert
     */
    private $advert;

    /**
     * @var Request
     */
    private $request;

    public function  __construct(Advert $advert, Request $request)
    {
        $this->request = $request;
        $this->advert = $advert;
    }

    /**
     * @return Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * @return Request
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}


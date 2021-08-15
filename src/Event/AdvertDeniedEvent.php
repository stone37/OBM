<?php

namespace App\Event;

use App\Entity\Advert;
use Symfony\Contracts\EventDispatcher\Event;

class AdvertDeniedEvent extends Event
{
    /**
     * @var Advert
     */
    private $advert;

    public function  __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    /**
     * @return Advert
     */
    public function getAdvert()
    {
        return $this->advert;
    }
}


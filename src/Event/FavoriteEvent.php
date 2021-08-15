<?php

namespace App\Event;

use App\Entity\Favorite;

class FavoriteEvent
{
    /**
     * @var Favorite
     */
    private $favorite;

    public function  __construct(Favorite $favorite)
    {
        $this->favorite = $favorite;
    }

    /**
     * @return Favorite
     */
    public function getFavorite(): Favorite
    {
        return $this->favorite;
    }
}


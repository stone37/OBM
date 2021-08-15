<?php

namespace App\Entity;

use App\Entity\Traits\AdvertAchatVenteTrait;
use App\Entity\Traits\AdvertAutoTrait;
use App\Entity\Traits\AdvertImmobilierTrait;
use App\Entity\Traits\AdvertOptionTrait;
use App\Entity\Traits\AdvertStatusTrait;
use App\Entity\Traits\AdvertTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Advert
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 * @ORM\MappedSuperclass
 */
class Advert
{
    use IdTrait;
    use AdvertTrait;
    use AdvertStatusTrait;
    use PositionTrait;
    use TimestampableTrait;
    use AdvertAutoTrait;
    use AdvertImmobilierTrait;
    use AdvertAchatVenteTrait;
    use AdvertOptionTrait;

    public function __construct()
    {
        $this->__constructAdvert();
    }
}



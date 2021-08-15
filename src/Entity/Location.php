<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\LocationTrait;
use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\MappedSuperclass
 */
class Location
{
    use IdTrait;
    use LocationTrait;
}

<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Command
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\MappedSuperclass
 */
class Order
{
    use IdTrait;

}


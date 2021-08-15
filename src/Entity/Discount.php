<?php

namespace App\Entity;

use App\Entity\Traits\DiscountTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\DiscountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    use IdTrait;
    use DiscountTrait;
    use TimestampableTrait;
}

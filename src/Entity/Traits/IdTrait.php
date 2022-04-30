<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait IdTrait
 * @package App\Entity\Traits
 */
trait IdTrait
{
    /**
     * @Groups({"read:category", "read:user", "read:premium", "read:advert", "read:thread",
     *          "read:picture", "read:favorite", "read:city", "read:zone", "read:alert"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}



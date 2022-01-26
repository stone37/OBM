<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ZoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ZoneRepository::class)
 */
class Zone
{
    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var City
     *
     * @Groups({"read:zone"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="zones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @Groups({"read:zone"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $zone;

    /**
     * @return City
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getZone(): ?string
    {
        return $this->zone;
    }

    /**
     * @param string $zone
     */
    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function __toString(): string
    {
        return $this->zone;
    }
}


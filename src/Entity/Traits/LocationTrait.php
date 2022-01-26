<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait LocationTrait
 * @package App\Entity\Traits
 */
trait LocationTrait
{
    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lng = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lat = null;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $detail = null;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getLng(): ?string
    {
        return $this->lng;
    }

    /**
     * @param string $lng
     */
    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return string
     */
    public function getLat(): ?string
    {
        return $this->lat;
    }

    /**
     * @param string $lat
     */
    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * @param string $detail
     */
    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}



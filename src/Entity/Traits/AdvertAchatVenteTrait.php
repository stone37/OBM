<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AdvertAchatVenteTrait
 * @package App\Entity\Traits
 */
trait AdvertAchatVenteTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"AchatVente"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $aType;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"Ipad", "Obureau", "Oportable"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $brand;

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getAType(): ?string
    {
        return $this->aType;
    }

    /**
     * @param string $aType
     */
    public function setAType(?string $aType): self
    {
        $this->aType = $aType;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}



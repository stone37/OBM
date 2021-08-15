<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait DiscountTrait
 * @package App\Entity\Traits
 */
trait DiscountTrait
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $utilisation;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $utiliser;

    /**
     * @return int
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     */
    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getUtilisation(): ?int
    {
        return $this->utilisation;
    }

    /**
     * @param int $utilisation
     */
    public function setUtilisation(?int $utilisation): self
    {
        $this->utilisation = $utilisation;

        return $this;
    }

    /**
     * @return int
     */
    public function getUtiliser(): ?int
    {
        return $this->utiliser;
    }

    /**
     * @param int $utiliser
     */
    public function setUtiliser(?int $utiliser): self
    {
        $this->utiliser = $utiliser;

        return $this;
    }
}



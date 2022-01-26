<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Trait AdvertStatusTrait
 * @package App\Entity\Traits
 */
trait AdvertStatusTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $denied = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deniedAt = null;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $deleted = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt = null;

    /**
     * @var bool
     *
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validated = false;

    /**
     * @var DateTime
     *
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validatedAt = null;

    /**
     * @return bool
     */
    public function isDenied(): ?bool
    {
        return $this->denied;
    }

    /**
     * @param bool $denied
     */
    public function setDenied(?bool $denied): self
    {
        $this->denied = $denied;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeniedAt(): ?DateTime
    {
        return $this->deniedAt;
    }

    /**
     * @param DateTime $deniedAt
     */
    public function setDeniedAt(?DateTime $deniedAt): self
    {
        $this->deniedAt = $deniedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTime $deletedAt
     */
    public function setDeletedAt(?DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     */
    public function setValidated(?bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getValidatedAt(): ?DateTime
    {
        return $this->validatedAt;
    }

    /**
     * @param DateTime $validatedAt
     */
    public function setValidatedAt(?DateTime $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }
}



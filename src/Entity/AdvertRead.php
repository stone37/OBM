<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Class Advert
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AdvertReadRepository::class)
 * @ORM\MappedSuperclass
 */
class AdvertRead
{
    use IdTrait;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Advert
     *
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="reads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Advert
     */
    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    /**
     * @param Advert $advert
     */
    public function setAdvert(Advert $advert): self
    {
        $this->advert = $advert;
        $advert->addRead($this);

        return $this;
    }


}



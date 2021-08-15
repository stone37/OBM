<?php

namespace App\Entity\Traits;

use App\Entity\OrderItem;
use App\Entity\Taxe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait ProductTrait
 * @package App\Entity\Traits
 */
trait ProductTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"category"}, unique=true)
     *
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @var int
     *
     * @Assert\NotBlank(groups={"CP", "OV", "V"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $days;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @var int
     *
     * @Assert\NotBlank(groups={"OP"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $photoFree;

    /**
     * @var int
     *
     * @Assert\NotBlank(groups={"OP"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $photoPaying;

    /**
     * @var int
     *
     * @Assert\NotBlank(groups={"CP"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $adNumber;

    /**
     * @var int
     *
     * @Assert\NotBlank(groups={"AC"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $credit;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @var OrderItem|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="product")
     */
    private $orderItems;

    /**
     * @var Taxe
     *
     * @ORM\ManyToOne(targetEntity=Taxe::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $taxe;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int
     */
    public function getDays(): ?int
    {
        return $this->days;
    }

    /**
     * @param int $days
     */
    public function setDays(?int $days): self
    {
        $this->days = $days;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int
     */
    public function getPhotoFree(): ?int
    {
        return $this->photoFree;
    }

    /**
     * @param int $photoFree
     */
    public function setPhotoFree(?int $photoFree): self
    {
        $this->photoFree = $photoFree;

        return $this;
    }

    /**
     * @return int
     */
    public function getPhotoPaying(): ?int
    {
        return $this->photoPaying;
    }

    /**
     * @param int $photoPaying
     */
    public function setPhotoPaying(?int $photoPaying): self
    {
        $this->photoPaying = $photoPaying;

        return $this;
    }

    /**
     * @return int
     */
    public function getAdNumber(): ?int
    {
        return $this->adNumber;
    }

    /**
     * @param int $adNumber
     */
    public function setAdNumber(?int $adNumber): self
    {
        $this->adNumber = $adNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getCredit(): ?int
    {
        return $this->credit;
    }

    /**
     * @param int $credit
     */
    public function setCredit(?int $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @return OrderItem|OrderItem[]|ArrayCollection
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $item): self
    {
        if (!$this->orderItems->contains($item)) {
            $this->orderItems[] = $item;
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $item): self
    {
        if ($this->orderItems->contains($item)) {
            $this->orderItems->removeElement($item);
        }

        return $this;
    }

    /**
     * @return Taxe
     */
    public function getTva(): ?Taxe
    {
        return $this->taxe;
    }

    /**
     * @param Taxe $tva
     */
    public function setTva(Taxe $tva): self
    {
        $this->taxe = $tva;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }
}


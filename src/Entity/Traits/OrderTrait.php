<?php

namespace App\Entity\Traits;

use App\Entity\Advert;
use App\Entity\Discount;
use App\Entity\OrderItem;
use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Trait OrderTrait
 * @package App\Entity\Traits
 */
trait OrderTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validated = false;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceTotal;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceTotalTva;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalTva;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $paymentMethod;

    /**
     * @var Payment
     *
     * @ORM\OneToOne(targetEntity=Payment::class, mappedBy="order")
     */
    private $payment;

    /**
     * @var OrderItem|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="order", cascade={"ALL"})
     */
    private $items;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Advert
     *
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="orders")
     */
    private $advert;

    /**
     * @var Discount
     *
     * @ORM\ManyToOne(targetEntity=Discount::class)
     */
    private $discount;

    public function __constructOrder()
    {
        $this->items = new ArrayCollection();
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
     * @return int
     */
    public function getReference(): ?int
    {
        return $this->reference;
    }

    /**
     * @param int $reference
     */
    public function setReference(?int $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceTotal(): ?int
    {
        return $this->priceTotal;
    }

    /**
     * @param int $priceTotal
     */
    public function setPriceTotal(?int $priceTotal): self
    {
        $this->priceTotal = $priceTotal;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem $item
     * @return $this
     */
    public function addItem(?OrderItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(?OrderItem $item): self
    {
        //$this->items->first()
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        $user->addOrder($this);

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
    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * @return Discount
     */
    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    /**
     * @param Discount $discount
     */
    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalTva(): ?float
    {
        return $this->totalTva;
    }

    /**
     * @param float $totalTva
     */
    public function setTotalTva(float $totalTva): self
    {
        $this->totalTva = $totalTva;

        return $this;
    }

    /**
     * @return float
     */
    public function getPriceTotalTva(): ?float
    {
        return $this->priceTotalTva;
    }

    /**
     * @param float $priceTotalTva
     */
    public function setPriceTotalTva(?float $priceTotalTva): self
    {
        $this->priceTotalTva = $priceTotalTva;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}


<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaymentRepository;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    const METHOD_WALLET = 'wallet';
    const METHOD_CARD = 'card';

    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $method = self::METHOD_CARD;

    /**
     * @var Command
     *
     * @ORM\OneToOne(targetEntity=Command::class, inversedBy="payment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tax = 0;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="deposits")
     */
    private $inWallet;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="purchases")
     */
    private $outWallet;

    /**
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(?string $method): self
    {
        $this->method = $method;

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
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return int
     */
    public function getTax(): ?int
    {
        return $this->tax;
    }

    /**
     * @param int $tax
     */
    public function setTax(?int $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return Command
     */
    public function getOrder(): ?Command
    {
        return $this->order;
    }

    /**
     * @param Command $order
     */
    public function setOrder(?Command $order): self
    {
        $this->order = $order;
        $order->setPayment($this);

        return $this;
    }

    /**
     * @return Wallet
     */
    public function getInWallet(): ?Wallet
    {
        return $this->inWallet;
    }

    /**
     * @param Wallet $inWallet
     */
    public function setInWallet(?Wallet $inWallet): self
    {
        $this->inWallet = $inWallet;

        return $this;
    }

    /**
     * @return Wallet
     */
    public function getOutWallet(): ?Wallet
    {
        return $this->outWallet;
    }

    /**
     * @param Wallet $outWallet
     */
    public function setOutWallet(?Wallet $outWallet): self
    {
        $this->outWallet = $outWallet;

        return $this;
    }
}



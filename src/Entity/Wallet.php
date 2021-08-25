<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\IdTrait;

/**
 * Class Wallet
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $deposit = 0; // depot

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $spent = 0; // depence

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private $balance = 0; // solde

    /**
     * @var Payment
     *
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="inWallet")
     */
    private $deposits; // Depot paye

    /**
     * @var Payment
     *
     * @ORM\OneToMany(targetEntity=Payment::class, mappedBy="outWallet")
     */
    private $purchases; // Achat paye

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="wallet")
     */
    private $user;

    public function __construct()
    {
        $this->deposits = new ArrayCollection();
        $this->purchases = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getDeposit(): ?int
    {
        return $this->deposit;
    }

    /**
     * @param int $deposit
     */
    public function setDeposit(int $deposit): self
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * @return int
     */
    public function getSpent(): ?int
    {
        return $this->spent;
    }

    /**
     * @param int $spent
     */
    public function setSpent(?int $spent): self
    {
        $this->spent = $spent;

        return $this;
    }

    /**
     * @return int
     */
    public function getBalance(): ?int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     */
    public function setBalance(?int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getDeposits()
    {
        return $this->deposits;
    }

    public function addDeposit(Payment $payment): self
    {
        if (!$this->deposits->contains($payment)) {
            $this->deposits[] = $payment;
            $payment->setInWallet($this);
        }

        return $this;
    }

    public function removeDeposit(Payment $payment): self
    {
        if ($this->deposits->contains($payment)) {
            $this->deposits->removeElement($payment);
        }

        return $this;
    }

    public function getPurchases()
    {
        return $this->purchases;
    }

    public function addPurchase(Payment $payment): self
    {
        if (!$this->purchases->contains($payment)) {
            $this->purchases[] = $payment;
            $payment->setOutWallet($this);
        }

        return $this;
    }

    public function removePurchase(Payment $payment): self
    {
        if ($this->purchases->contains($payment)) {
            $this->purchases->removeElement($payment);
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
     * @param User $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

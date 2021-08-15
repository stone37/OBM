<?php

namespace App\Entity\Traits;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait PremiumTrait
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    protected $premiumEnd = null;

    public function isPremium(): bool
    {
        return $this->premiumEnd > new DateTime();
    }

    public function getPremiumEnd(): ?DateTimeImmutable
    {
        return $this->premiumEnd;
    }

    public function setPremiumEnd(?DateTimeImmutable $premiumEnd): self
    {
        $this->premiumEnd = $premiumEnd;

        return $this;
    }
}



<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait SocialLoggableTrait
{
    /**
     * @Groups({"read:user", "write:user"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleId = null;

    /**
     * @Groups({"read:user", "write:user"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookId = null;

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    public function setFacebookId(?string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function useOauth(): bool
    {
        return null !== $this->googleId || null !== $this->facebookId;
    }
}

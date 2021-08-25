<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository"))
 */
class Invitation
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $code;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        // generate identifier only once, here a 6 characters length code
        $this->code = substr(md5(uniqid(rand(), true)), 0, 6);
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
     * @return User
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}

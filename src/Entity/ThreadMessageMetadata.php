<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadMessageMetadataRepository;

/**
 * Class MessageMetadata
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=ThreadMessageMetadataRepository::class)
 * @ORM\MappedSuperclass
 */
class ThreadMessageMetadata
{
    use IdTrait;

    /**
     * @var ThreadMessage
     *
     * @ORM\ManyToOne(targetEntity=ThreadMessage::class, inversedBy="metadata")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $participant;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRead = false;


    /**
     * @return ThreadMessage
     */
    public function getMessage(): ThreadMessage
    {
        return $this->message;
    }

    /**
     * @param ThreadMessage $message
     */
    public function setMessage(ThreadMessage $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return User
     */
    public function getParticipant(): User
    {
        return $this->participant;
    }

    /**
     * @param User $participant
     */
    public function setParticipant(User $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function getIsRead(): bool
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     */
    public function setIsRead(bool $isRead)
    {
        $this->isRead = (bool) $isRead;
    }
}



<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadMetadataRepository;

/**
 * Class ThreadMetadata
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=ThreadMetadataRepository::class)
 * @ORM\MappedSuperclass
 */
class ThreadMetadata
{
    use IdTrait;

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
    private $isDeleted = false;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastParticipantMessageDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastMessageDate;

    /**
     * @var Thread
     *
     * @ORM\ManyToOne(targetEntity=Thread::class, inversedBy="metadata")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thread;

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

    /**
     * @return bool
     */
    public function isDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(?bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastParticipantMessageDate(): ?DateTime
    {
        return $this->lastParticipantMessageDate;
    }

    /**
     * @param DateTime $lastParticipantMessageDate
     */
    public function setLastParticipantMessageDate(?DateTime $lastParticipantMessageDate): self
    {
        $this->lastParticipantMessageDate = $lastParticipantMessageDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLastMessageDate(): ?DateTime
    {
        return $this->lastMessageDate;
    }

    /**
     * @param DateTime $lastMessageDate
     */
    public function setLastMessageDate(?DateTime $lastMessageDate): self
    {
        $this->lastMessageDate = $lastMessageDate;

        return $this;
    }

    /**
     * @return Thread
     */
    public function getThread(): Thread
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     */
    public function setThread(Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }
}



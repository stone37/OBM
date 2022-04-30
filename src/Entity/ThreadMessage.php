<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadMessageRepository;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ThreadMessage
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=ThreadMessageRepository::class)
 * @ORM\MappedSuperclass
 */
class ThreadMessage
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var User
     *
     * @Groups({"read:thread"})
     *
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $sender;

    /**
     * @var String
     *
     * @Groups({"read:thread"})
     *
     * @Assert\NotBlank(message="Veuillez renseigne votre message")
     * @Assert\Length(min=2, minMessage="Votre message doit contenir au moin {{ limit }} caractères")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @var Thread
     *
     * @ORM\ManyToOne(targetEntity=Thread::class, inversedBy="messages")
     */
    private $thread;

    /**
     * @var Collection|ThreadMessageMetadata
     *
     * @ORM\OneToMany(targetEntity=ThreadMessageMetadata::class, mappedBy="message", cascade={"ALL"})
     */
    private $metadata;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->metadata = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getSender(): User
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender(User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return String
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param String $body
     */
    public function setBody(?string $body): self
    {
        $this->body = $body;

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

    public function getMetadataForParticipant(User $participant)
    {
        foreach ($this->metadata as $meta) {
            if ($meta->getParticipant()->getId() == $participant->getId()) {
                return $meta;
            }
        }

        return null;
    }

    public function addMetadata(ThreadMessageMetadata $meta)
    {
        if (!$this->metadata->contains($meta)) {
            $this->metadata->add($meta);
            $meta->setMessage($this);
        }

        return $this;
    }

    public function isReadByParticipant(User $user)
    {
        if ($meta = $this->getMetadataForParticipant($user)) {
            return $meta->getIsRead();
        }

        return false;
    }

    public function setIsReadByParticipant(User $user, $isRead)
    {
        if (!$meta = $this->getMetadataForParticipant($user)) {
            throw new InvalidArgumentException(sprintf('No metadata exists for participant with id "%s"', $user->getId()));
        }

        $meta->setIsRead($isRead);
    }

    public function getTimestamp()
    {
        return $this->getCreatedAt()->getTimestamp();
    }
}



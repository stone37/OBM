<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ThreadRepository;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Thread
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=ThreadRepository::class)
 * @ORM\MappedSuperclass
 */
class Thread
{
    use IdTrait;

    /**
     * @var Advert
     *
     * @Groups({"read:thread"})
     *
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="threads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    /**
     * @var Collection|ThreadMessage
     *
     * @Groups({"read:thread"})
     *
     * @ORM\OneToMany(targetEntity=ThreadMessage::class, mappedBy="thread", cascade={"ALL"})
     */
    private $messages;

    /**
     * @var Collection|ThreadMetadata
     *
     * @Groups({"read:thread"})
     *
     * @ORM\OneToMany(targetEntity=ThreadMetadata::class, mappedBy="thread", cascade={"ALL"})
     */
    private $metadata;

    /**
     * @var User
     *
     * @Groups({"read:thread"})
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="threads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * Users participating in this conversation.
     *
     * @var Collection|User
     */
    protected $participants;

    /**
     * @var DateTime
     *
     * @Groups({"read:thread"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->metadata = new ArrayCollection();
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
    public function setAdvert(Advert $advert): self
    {
        $this->advert = $advert;
        $advert->addThread($this);

        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;
        $createdBy->addThread($this);

        return $this;
    }

    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    /**
     * @param ThreadMessage $message
     * @return $this
     */
    public function addMessage(ThreadMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setThread($this);
        }

        return $this;
    }

    public function removeMessage(ThreadMessage $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
        }

        return $this;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function addMetadata(ThreadMetadata $meta)
    {
        if (!$this->metadata->contains($meta)) {
            $this->metadata->add($meta);
            $meta->setThread($this);
        }

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFirstMessage()
    {
        return $this->getMessages()->first();
    }

    public function getLastMessage()
    {
        return $this->getMessages()->last();
    }


    public function isDeletedByParticipant(User $user)
    {
        if ($meta = $this->getMetadataForParticipant($user)) {
            return $meta->getIsDeleted();
        }

        return false;
    }

    public function setIsDeletedByParticipant(User $user, $isDeleted)
    {
        if (!$meta = $this->getMetadataForParticipant($user)) {
            throw new InvalidArgumentException(sprintf('No metadata exists for participant with id "%s"', $user->getId()));
        }

        $meta->setIsDeleted($isDeleted);

        if ($isDeleted) {
            // also mark all thread messages as read
            foreach ($this->getMessages() as $message) {
                $message->setIsReadByParticipant($user, true);
            }
        }
    }

    public function setIsDeleted($isDeleted)
    {
        foreach ($this->getParticipants() as $participant) {
            $this->setIsDeletedByParticipant($participant, $isDeleted);
        }
    }

    public function isReadByParticipant(User $user)
    {
        foreach ($this->getMessages() as $message) {
            if (!$message->isReadByParticipant($user)) {
                return false;
            }
        }

        return true;
    }

    public function setIsReadByParticipant(User $user, $isRead)
    {
        foreach ($this->getMessages() as $message) {
            $message->setIsReadByParticipant($user, $isRead);
        }
    }

   /* public function isParticipant(User $user)
    {
        foreach ($this->getParticipants() as $participant) {
            if ($participant->getId() == $user->getId()) {
                return true;
            }
        }

        return false;
    }*/

    public function addParticipant(User $user)
    {
        if (!$this->isParticipant($user)) {
            $this->getParticipantsCollection()->add($user);
        }
    }

    /* public function getParticipants()
     {
         $participants = [];
         $participants[] = $this->advert->getUser();
         $participants[] = $this->createdBy;

         return $participants;
     }*/

    /**
     * @Groups({"read:thread"})
     *
     * @return array|mixed[]
     */
    public function getParticipants()
    {
        return $this->getParticipantsCollection()->toArray();
    }

    public function getMetadataForParticipant(User $user)
    {
        foreach ($this->metadata as $meta) {
            if ($meta->getParticipant()->getId() == $user->getId()) {
                return $meta;
            }
        }

        return null;
    }


    public function isParticipant(User $user)
    {
        return $this->getParticipantsCollection()->contains($user);
    }

    protected function getParticipantsCollection()
    {
        if (null === $this->participants) {
            $this->participants = new ArrayCollection();

            foreach ($this->metadata as $data) {
                $this->participants->add($data->getParticipant());
            }
        }

        return $this->participants;
    }
}





<?php

namespace App\Entity\Traits;

use App\Entity\AdvertPicture;
use App\Entity\AdvertRead;
use App\Entity\Category;
use App\Entity\Favorite;
use App\Entity\Location;
use App\Entity\Message;
use App\Entity\Command;
use App\Entity\Thread;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait AdvertTrait
 * @package App\Entity\Traits
 */
trait AdvertTrait
{
    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="8", max="120")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $title; 

    /**
     * @var string
     *
     * @Groups({"read:advert"})
     *
     * @Gedmo\Slug(fields={"title"}, unique=true)
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="10")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price = 0;

    /**
     * @var bool
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $priceStatus = false;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $traitement;

    /**
     * @var Category
     *
     * @Groups({"read:advert"})
     *
     * @Assert\Valid()
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var Category
     *
     * @Groups({"read:advert"})
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $subCategory;

    /**
     * @var Category
     *
     * @Groups({"read:advert"})
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $subDivision;

    /**
     * @var Location
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=Location::class, cascade={"ALL"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @var ArrayCollection|AdvertPicture
     *
     * @Groups({"read:advert"})
     *
     * @ORM\OneToMany(targetEntity=AdvertPicture::class, mappedBy="advert", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @var User
     *
     * @Groups({"read:advert"})
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adverts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @var Command|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=Command::class, mappedBy="advert")
     */
    private $orders;

    /**
     * @var Favorite|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="advert", cascade={"remove"})
     */
    private $favorites;

    /**
     * @var int
     *
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $messageCount = 0;

    /**
     * @var Message|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="advert")
     * @ORM\OrderBy({"accepted"="DESC", "createdAt"="ASC"})
     */
    private $messages;

    /**
     * @var Message
     *
     * @ORM\ManyToOne(targetEntity=Message::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $lastMessage = null;

    /**
     * @var AdvertRead|Collection
     *
     * @ORM\OneToMany(targetEntity=AdvertRead::class, mappedBy="advert")
     */
    private $reads;

    /**
     * @var Thread|Collection
     *
     * @ORM\OneToMany(targetEntity=Thread::class, mappedBy="advert")
     */
    private $threads;

    private $shop;



    public function __constructAdvert()
    {
        $this->images = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->reads = new ArrayCollection();
        $this->threads = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * @return bool
     */
    public function isPriceStatus(): ?bool
    {
        return $this->priceStatus;
    }

    /**
     * @param bool $priceStatus
     */
    public function setPriceStatus(?bool $priceStatus): self
    {
        $this->priceStatus = $priceStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    /**
     * @param string $traitement
     */
    public function setTraitement(?string $traitement): self
    {
        $this->traitement = $traitement;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getSubCategory(): ?Category
    {
        return $this->subCategory;
    }

    /**
     * @param Category $subCategory
     */
    public function setSubCategory(?Category $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * @return Category
     */
    public function getSubDivision(): ?Category
    {
        return $this->subDivision;
    }

    /**
     * @param Category $subDivision
     */
    public function setSubDivision(?Category $subDivision): self
    {
        $this->subDivision = $subDivision;

        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @param AdvertPicture $picture
     */
    public function addImage(?AdvertPicture $picture): self
    {
        if (!$this->images->contains($picture)) {
            $this->images[] = $picture;
            $picture->setAdvert($this);
        }

        return $this;
    }

    /**
     * @param AdvertPicture $picture
     */
    public function removeImage(AdvertPicture $picture): self
    {
        if ($this->images->contains($picture)) {
            $this->images->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getAdvert() === $this) {
                $picture->setAdvert(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getImages(): ?Collection
    {
        return $this->images;
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
        $user->addAdverts($this);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrders(): ?Collection
    {
        return $this->orders;
    }

    public function addOrder(Command $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
        }

        return $this;
    }

    public function removeOrder(Command $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFavorites(): ?Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
        }

        return $this;
    }

    public function getMessageCount(): ?int
    {
        return $this->messageCount;
    }

    public function setMessageCount(?int $messageCount): self
    {
        $this->messageCount = $messageCount;

        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->lastMessage;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAdvert($this);
        }

        return $this;
    }

    /**
     * @param Collection|Message[] $messages
     */
    public function setMessages(Collection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function setLastMessage(?Message $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    public function isLocked(): bool
    {
        return $this->getCreatedAt() < (new \DateTime('-6 month'));
    }

    /**
     * @return Collection|AdvertRead[]
     */
    public function getReads(): ?Collection
    {
        return $this->reads;
    }

    /**
     * @param AdvertRead $read
     * @return $this
     */
    public function addRead(AdvertRead $read): self
    {
        if (!$this->reads->contains($read)) {
            $this->reads->add($read);
        }

        return $this;
    }

    public function removeRead(AdvertRead $read): self
    {
        if ($this->reads->contains($read)) {
            $this->reads->removeElement($read);
        }

        return $this;
    }

    /**
     * @return Collection|Thread[]
     */
    public function getThreads(): ?Collection
    {
        return $this->threads;
    }

    /**
     * @param Thread $thread
     * @return $this
     */
    public function addThread(Thread $thread): self
    {
        if (!$this->threads->contains($thread)) {
            $this->threads->add($thread);
        }

        return $this;
    }

    public function removeThread(Thread $thread): self
    {
        if ($this->threads->contains($thread)) {
            $this->threads->removeElement($thread);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     */
    public function setShop($shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}



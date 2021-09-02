<?php

namespace App\Entity\Traits;

use App\Entity\Advert;
use App\Entity\Alert;
use App\Entity\Favorite;
use App\Entity\Command;
use App\Entity\Invitation;
use App\Entity\Wallet;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Trait UserTrait
 * @package App\Entity\Traits
 */
trait UserTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un prénom s'il vous plait.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min="2",
     *     max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un prénom s'il vous plait.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min="2",
     *     max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="Entrez un numéro de téléphone s''il vous plait.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min="10",
     *     max="20",
     *     minMessage="Le numéro de téléphone est trop court.",
     *     maxMessage="Le numéro de téléphone est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $phoneStatus;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="user",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    private $file;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var ArrayCollection|Advert
     *
     * @ORM\OneToMany(targetEntity=Advert::class, mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $adverts;

    /**
     * @var ArrayCollection|Command
     *
     * @ORM\OneToMany(targetEntity=Command::class, mappedBy="user", cascade={"ALL"}, orphanRemoval=true)
     */
    private $orders;

    /**
     * @var ArrayCollection|Favorite
     *
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $favorites;

    /**
     * @var ArrayCollection|Alert
     *
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="user", cascade={"remove"}, orphanRemoval=true)
     */
    private $alerts;

    /**
     * @var Wallet
     *
     * @ORM\OneToOne(targetEntity=Wallet::class, inversedBy="user", cascade={"ALL"})
     */
    private $wallet;

    /**
     * @var Invitation
     *
     * @ORM\OneToOne(targetEntity=Invitation::class)
     */
    private $invitation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $twitterAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $instagramAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $linkedinAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $youtubeAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $webSite;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyCity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyDistrict;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $drift = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $parrainageDrift = false;

    public function __constructUser()
    {
        $this->createdAt = new DateTime();
        $this->adverts = new ArrayCollection();
        $this->orders  = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->alerts  = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPhoneStatus(): ?bool
    {
        return $this->phoneStatus;
    }

    /**
     * @param bool $phoneStatus
     */
    public function setPhoneStatus(?bool $phoneStatus): self
    {
        $this->phoneStatus = $phoneStatus;
        
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDay(): ?DateTime
    {
        return $this->birthDay;
    }

    /**
     * @param DateTime $birthDay
     */
    public function setBirthDay(?DateTime $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * @param Advert $advert
     */
    public function addAdverts(?Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts[] = $advert;
        }

        return $this;
    }

    /**
     * @param Advert $advert
     */
    public function removeAdverts(Advert $advert): self
    {
        $this->adverts->removeElement($advert);

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getAdverts(): ?Collection
    {
        return $this->adverts;
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

    /**
     * @return Collection
     */
    public function getAlerts(): ?Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
        }

        return $this;
    }

    /**
     * @return Wallet
     */
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    /**
     * @param Wallet $wallet
     */
    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;
        $wallet->setUser($this);

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $image
     */
    public function setFile(?File $image = null): self
    {
        $this->file = $image;

        if (null !== $image) {
            $this->updatedAt = new DateTimeImmutable();
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
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAddress(): ?string
    {
        return $this->facebookAddress;
    }

    /**
     * @param string $facebookAddress
     */
    public function setFacebookAddress(?string $facebookAddress): self
    {
        $this->facebookAddress = $facebookAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getInstagramAddress(): ?string
    {
        return $this->instagramAddress;
    }

    /**
     * @param string $instagramAddress
     */
    public function setInstagramAddress(?string $instagramAddress): self
    {
        $this->instagramAddress = $instagramAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitterAddress(): ?string
    {
        return $this->twitterAddress;
    }

    /**
     * @param string $twitterAddress
     */
    public function setTwitterAddress(?string $twitterAddress): self
    {
        $this->twitterAddress = $twitterAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedinAddress(): ?string
    {
        return $this->linkedinAddress;
    }

    /**
     * @param string $linkedinAddress
     */
    public function setLinkedinAddress(?string $linkedinAddress): self
    {
        $this->linkedinAddress = $linkedinAddress;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    /**
     * @param string $webSite
     */
    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSocietyCity(): ?string
    {
        return $this->societyCity;
    }

    /**
     * @param string $societyCity
     */
    public function setSocietyCity(?string $societyCity): self
    {
        $this->societyCity = $societyCity;

        return $this;
    }

    /**
     * @return string
     */
    public function getSocietyDistrict(): ?string
    {
        return $this->societyDistrict;
    }

    /**
     * @param string $societyDistrict
     */
    public function setSocietyDistrict(?string $societyDistrict): self
    {
        $this->societyDistrict = $societyDistrict;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string
     */
    public function getYoutubeAddress(): ?string
    {
        return $this->youtubeAddress;
    }

    /**
     * @param string $youtubeAddress
     */
    public function setYoutubeAddress(?string $youtubeAddress): self
    {
        $this->youtubeAddress = $youtubeAddress;

        return $this;
    }
    
    public function __toString()
    {
        return (string) ucfirst($this->getFirstName()) . ' ' . ucfirst($this->getLastName());
    }

    /**
     * @return Invitation
     */
    public function getInvitation(): ?Invitation
    {
        return $this->invitation;
    }

    /**
     * @param Invitation $invitation
     */
    public function setInvitation(?Invitation $invitation): self
    {
        $this->invitation = $invitation;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDrift(): ?bool
    {
        return $this->drift;
    }

    /**
     * @param bool $drift
     */
    public function setDrift(?bool $drift): self
    {
        $this->drift = $drift;

        return $this;
    }

    /**
     * @return bool
     */
    public function isParrainageDrift(): ?bool
    {
        return $this->parrainageDrift;
    }

    /**
     * @param bool $parrainageDrift
     */
    public function setParrainageDrift(?bool $parrainageDrift): self
    {
        $this->parrainageDrift = $parrainageDrift;

        return $this;
    }
}


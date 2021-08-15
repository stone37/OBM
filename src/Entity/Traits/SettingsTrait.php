<?php

namespace App\Entity\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Trait SettingsTrait
 * @package App\Entity\Traits
 */
trait SettingsTrait
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $district;

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
    private $youtubeAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $linkedinAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeMessage;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeAdFavorite;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeAlert;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeAdSimilar;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberAdList;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberAdUserList;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberFavoriteUserList;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeCredit = true;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeCardPayment = true;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activeVignette = true;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activePub = true;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="settings",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    private $file;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

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
     * @return string
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

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
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

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
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * @param string $district
     */
    public function setDistrict(?string $district): self
    {
        $this->district = $district;

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

    /**
     * @return bool
     */
    public function isActiveMessage(): ?bool
    {
        return $this->activeMessage;
    }

    /**
     * @param bool $activeMessage
     */
    public function setActiveMessage(?bool $activeMessage): self
    {
        $this->activeMessage = $activeMessage;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveAdFavorite(): ?bool
    {
        return $this->activeAdFavorite;
    }

    /**
     * @param bool $activeAdFavorite
     */
    public function setActiveAdFavorite(?bool $activeAdFavorite): self
    {
        $this->activeAdFavorite = $activeAdFavorite;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveAlert(): ?bool
    {
        return $this->activeAlert;
    }

    /**
     * @param bool $activeAlert
     */
    public function setActiveAlert(?bool $activeAlert): self
    {
        $this->activeAlert = $activeAlert;

        return $this;
    }

    /**
     * @param bool $activeShop
     */
    public function setActiveShop(?bool $activeShop): self
    {
        $this->activeShop = $activeShop;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveAdSimilar(): ?bool
    {
        return $this->activeAdSimilar;
    }

    /**
     * @param bool $activeAdSimilar
     */
    public function setActiveAdSimilar(?bool $activeAdSimilar): self
    {
        $this->activeAdSimilar = $activeAdSimilar;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberAdList(): ?int
    {
        return $this->numberAdList;
    }

    /**
     * @param int $numberAdList
     */
    public function setNumberAdList(?int $numberAdList): self
    {
        $this->numberAdList = $numberAdList;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberAdUserList(): ?int
    {
        return $this->numberAdUserList;
    }

    /**
     * @param int $numberAdUserList
     */
    public function setNumberAdUserList(?int $numberAdUserList): self
    {
        $this->numberAdUserList = $numberAdUserList;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberFavoriteUserList(): ?int
    {
        return $this->numberFavoriteUserList;
    }

    /**
     * @param int $numberFavoriteUserList
     */
    public function setNumberFavoriteUserList(?int $numberFavoriteUserList): self
    {
        $this->numberFavoriteUserList = $numberFavoriteUserList;

        return $this;
    }

    /**
     * @param bool $activeAdOption
     */
    public function setActiveAdOption(?bool $activeAdOption): self
    {
        $this->activeAdOption = $activeAdOption;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveCredit(): ?bool
    {
        return $this->activeCredit;
    }

    /**
     * @param bool $activeCredit
     */
    public function setActiveCredit(?bool $activeCredit): self
    {
        $this->activeCredit = $activeCredit;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveVignette(): ?bool
    {
        return $this->activeVignette;
    }

    /**
     * @param bool $activeVignette
     */
    public function setActiveVignette(?bool $activeVignette): self
    {
        $this->activeVignette = $activeVignette;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActiveCardPayment(): ?bool
    {
        return $this->activeCardPayment;
    }

    /**
     * @param bool $activeCardPayment
     */
    public function setActiveCardPayment(?bool $activeCardPayment): self
    {
        $this->activeCardPayment = $activeCardPayment;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivePub(): ?bool
    {
        return $this->activePub;
    }

    /**
     * @param bool $activePub
     */
    public function setActivePub(?bool $activePub): self
    {
        $this->activePub = $activePub;

        return $this;
    }
}



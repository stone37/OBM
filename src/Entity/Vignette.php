<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\MediaTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\VignetteRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Vignette
 * @package App\Entity
 *
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=VignetteRepository::class)
 * @ORM\MappedSuperclass
 */
class Vignette
{
    use IdTrait;
    use PositionTrait;
    use EnabledTrait;
    use TimestampableTrait;
    use MediaTrait;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $startDate = null;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $endDate = null;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $category = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $subCategory = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="190")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $siret = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="180")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstname = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="180")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastname = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="180")
     * @Assert\Email()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="20")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyName = "";

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $siteWeb = "";

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference = "";

    /**
     * @var File
     *
     * @Assert\File(maxSize="8M")
     * @Assert\NotBlank()
     *
     * @Vich\UploadableField(
     *     mapping="vignette",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    private $file;

    /**
     * @return DateTime
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate(?DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate(?DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubCategory(): ?string
    {
        return $this->subCategory;
    }

    /**
     * @param string $subCategory
     */
    public function setSubCategory(?string $subCategory): self
    {
        $this->subCategory = $subCategory;

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
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @param string $siret
     */
    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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
    public function getSiteWeb(): string
    {
        return $this->siteWeb;
    }

    /**
     * @param string $siteWeb
     */
    public function setSiteWeb(string $siteWeb): void
    {
        $this->siteWeb = $siteWeb;
    }

    /**
     * @return string
     */
    public function getSocietyName(): string
    {
        return $this->societyName;
    }

    /**
     * @param string $societyName
     */
    public function setSocietyName(string $societyName): void
    {
        $this->societyName = $societyName;
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
}



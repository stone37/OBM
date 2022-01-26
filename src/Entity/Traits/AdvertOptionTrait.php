<?php

namespace App\Entity\Traits;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait AdvertOptionTrait
{
    /**
     * @var boolean
     *
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $optionPhoto = false;

    /**
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $optionAdHeadEnd = null;

    /**
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $optionAdUrgentsEnd = null;

    /**
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $optionAdGalleryEnd = null;

    /**
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $optionAdVedetteEnd = null;

    /**
     * @Groups({"read:advert"})
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $optionAdEncadreEnd = null;


    /**
     * @return bool
     */
    public function isOptionPhoto(): ?bool
    {
        return $this->optionPhoto;
    }

    /**
     * @param bool $optionPhoto
     */
    public function setOptionPhoto(?bool $optionPhoto): self
    {
        $this->optionPhoto = $optionPhoto;

        return $this;
    }

    public function isOptionAdHead(): ?bool
    {
        return $this->optionAdHeadEnd > new DateTime();
    }

    /**
     * @return null
     */
    public function getOptionAdHeadEnd(): ?DateTimeImmutable
    {
        return $this->optionAdHeadEnd;
    }

    /**
     * @param null $optionAdHeadEnd
     */
    public function setOptionAdHeadEnd(?DateTimeImmutable $optionAdHeadEnd): self
    {
        $this->optionAdHeadEnd = $optionAdHeadEnd;

        return $this;
    }

    public function isOptionAdUrgents(): ?bool
    {
        return $this->optionAdUrgentsEnd > new DateTime();
    }

    /**
     * @return null
     */
    public function getOptionAdUrgentsEnd(): ?DateTimeImmutable
    {
        return $this->optionAdUrgentsEnd;
    }

    /**
     * @param null $optionAdUrgentsEnd
     */
    public function setOptionAdUrgentsEnd(?DateTimeImmutable $optionAdUrgentsEnd): self
    {
        $this->optionAdUrgentsEnd = $optionAdUrgentsEnd;

        return $this;
    }

    public function isOptionAdGallery(): ?bool
    {
        return $this->optionAdGalleryEnd > new DateTime();
    }

    /**
     * @return null
     */
    public function getOptionAdGalleryEnd(): ?DateTimeImmutable
    {
        return $this->optionAdGalleryEnd;
    }

    /**
     * @param null $optionAdGalleryEnd
     */
    public function setOptionAdGalleryEnd(?DateTimeImmutable $optionAdGalleryEnd): self
    {
        $this->optionAdGalleryEnd = $optionAdGalleryEnd;

        return $this;
    }

    public function isOptionAdVedette(): ?bool
    {
        return $this->optionAdVedetteEnd > new DateTime();
    }

    /**
     * @return null
     */
    public function getOptionAdVedetteEnd(): ?DateTimeImmutable
    {
        return $this->optionAdVedetteEnd;
    }

    /**
     * @param null $optionAdVedetteEnd
     */
    public function setOptionAdVedetteEnd(?DateTimeImmutable $optionAdVedetteEnd): self
    {
        $this->optionAdVedetteEnd = $optionAdVedetteEnd;

        return $this;
    }

    public function isOptionAdEncadre(): ?bool
    {
        return $this->optionAdEncadreEnd > new DateTime();
    }

    /**
     * @return null
     */
    public function getOptionAdEncadreEnd(): ?DateTimeImmutable
    {
        return $this->optionAdEncadreEnd;
    }

    /**
     * @param null $optionAdEncadreEnd
     */
    public function setOptionAdEncadreEnd(?DateTimeImmutable $optionAdEncadreEnd): self
    {
        $this->optionAdEncadreEnd = $optionAdEncadreEnd;

        return $this;
    }
}



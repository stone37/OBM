<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AdvertAutoTrait
 * @package App\Entity\Traits
 */
trait AdvertAutoTrait
{
    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "LV", "MC", "MS", "MR", "VBM", "JSSM"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $marque;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "LV"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "LV", "MS", "MR", "CL"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $autoYear;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "LV"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $autoType;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "MC", "MS", "MR"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $autoState;

    /**
     * @var integer
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @Assert\NotBlank(groups={"VO", "MS", "MR", "CL"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kilo;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $boiteVitesse;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $transmission;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $autoColor;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $typeCarburant;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nombrePorte;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nombrePlace;

    /**
     * @var array
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $autreInformation;

    /**
     * @var int
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cylindree;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $autoSecurity;

    /**
     * @var string
     *
     * @Groups({"read:advert", "write:advert", "update:advert"})
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $informationExterieur;

    /**
     * @return string
     */
    public function getMarque(): ?string
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     */
    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoYear(): ?string
    {
        return $this->autoYear;
    }

    /**
     * @param string $autoYear
     */
    public function setAutoYear(?string $autoYear): self
    {
        $this->autoYear = $autoYear;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoType(): ?string
    {
        return $this->autoType;
    }

    /**
     * @param string $autoType
     */
    public function setAutoType(?string $autoType): self
    {
        $this->autoType = $autoType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoState(): ?string
    {
        return $this->autoState;
    }

    /**
     * @param string $autoState
     */
    public function setAutoState(?string $autoState): self
    {
        $this->autoState = $autoState;

        return $this;
    }

    /**
     * @param int $kilo
     */
    public function setKilo(?int $kilo): self
    {
        $this->kilo = $kilo;

        return $this;
    }

    /**
     * @return int
     */
    public function getKilo(): ?int
    {
        return $this->kilo;
    }

    /**
     * @return string
     */
    public function getBoiteVitesse(): ?string
    {
        return $this->boiteVitesse;
    }

    /**
     * @param string $boiteVitesse
     */
    public function setBoiteVitesse(?string $boiteVitesse): self
    {
        $this->boiteVitesse = $boiteVitesse;

        return $this;
    }

    /**
     * @return string
     */
    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    /**
     * @param string $transmission
     */
    public function setTransmission(?string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoColor(): ?string
    {
        return $this->autoColor;
    }

    /**
     * @param string $autoColor
     */
    public function setAutoColor(?string $autoColor): self
    {
        $this->autoColor = $autoColor;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeCarburant(): ?string
    {
        return $this->typeCarburant;
    }

    /**
     * @param string $typeCarburant
     */
    public function setTypeCarburant(?string $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombrePorte(): ?string
    {
        return $this->nombrePorte;
    }

    /**
     * @param string $nombrePorte
     */
    public function setNombrePorte(?string $nombrePorte): self
    {
        $this->nombrePorte = $nombrePorte;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombrePlace(): ?string
    {
        return $this->nombrePlace;
    }

    /**
     * @param string $nombrePlace
     */
    public function setNombrePlace(?string $nombrePlace): self
    {
        $this->nombrePlace = $nombrePlace;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutreInformation(): ?array
    {
        return $this->autreInformation;
    }

    /**
     * @param string $autreInformation
     */
    public function setAutreInformation(?array $autreInformation): self
    {
        $this->autreInformation = $autreInformation;

        return $this;
    }

    /**
     * @return int
     */
    public function getCylindree(): ?int
    {
        return $this->cylindree;
    }

    /**
     * @param int $cylindree
     */
    public function setCylindree(?int $cylindree): self
    {
        $this->cylindree = $cylindree;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutoSecurity(): ?array
    {
        return $this->autoSecurity;
    }

    /**
     * @param string $autoSecurity
     */
    public function setAutoSecurity(?array $autoSecurity): self
    {
        $this->autoSecurity = $autoSecurity;

        return $this;
    }

    /**
     * @return string
     */
    public function getInformationExterieur(): ?array
    {
        return $this->informationExterieur;
    }

    /**
     * @param string $informationExterieur
     */
    public function setInformationExterieur(?array $informationExterieur): self
    {
        $this->informationExterieur = $informationExterieur;

        return $this;
    }
}



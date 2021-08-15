<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AdvertImmobilierTrait
 * @package App\Entity\Traits
 */
trait AdvertImmobilierTrait
{
    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"Appart", "Maison", "Chambre", "Duplex", "Studio", "StudioA", "Terrain",
     *     "Colocation", "Villa", "Bureaux", "Parking", "Autre"}
     * )
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surface;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"Appart", "Maison", "Duplex", "Villa"}
     * )
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nombrePiece;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     groups={"Appart", "Maison", "Chambre", "Duplex", "Studio", "StudioA", "Colocation",
     *     "Villa", "Bureaux"}
     * )
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $immobilierState;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $access;

    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"Appart", "Maison", "Duplex", "Villa"}
     * )
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreChambre;

    /**
     * @var int
     *
     * @Assert\NotBlank(
     *     groups={"Appart", "Maison", "Duplex", "Villa"}
     * )
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreSalleBain;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $surfaceBalcon;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $exterior;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $dateConstruction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $situation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $standing;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $cuisine;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $salleManger;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombrePlacard;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $interior;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $serviceInclus;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $typeSol;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $proximite;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $stateGenerale;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $facade;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $toiture;

    /**
     * @return int
     */
    public function getSurface(): ?int
    {
        return $this->surface;
    }

    /**
     * @param int $surface
     */
    public function setSurface(?int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * @return string
     */
    public function getNombrePiece(): ?string
    {
        return $this->nombrePiece;
    }

    /**
     * @param string $nombrePiece
     */
    public function setNombrePiece(?string $nombrePiece): self
    {
        $this->nombrePiece = $nombrePiece;

        return $this;
    }

    /**
     * @return string
     */
    public function getImmobilierState(): ?string
    {
        return $this->immobilierState;
    }

    /**
     * @param string $immobilierState
     */
    public function setImmobilierState(?string $immobilierState): self
    {
        $this->immobilierState = $immobilierState;

        return $this;
    }

    /**
     * @return array
     */
    public function getAccess(): ?array
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess(?array $access): self
    {
        $this->access = $access;

        return $this;
    }

    /**
     * @return int
     */
    public function getNombreChambre(): ?int
    {
        return $this->nombreChambre;
    }

    /**
     * @param int $nombreChambre
     */
    public function setNombreChambre(?int $nombreChambre): self
    {
        $this->nombreChambre = $nombreChambre;

        return $this;
    }

    /**
     * @return int
     */
    public function getNombreSalleBain(): ?int
    {
        return $this->nombreSalleBain;
    }

    /**
     * @param int $nombreSalleBain
     */
    public function setNombreSalleBain(?int $nombreSalleBain): self
    {
        $this->nombreSalleBain = $nombreSalleBain;

        return $this;
    }

    /**
     * @return int
     */
    public function getSurfaceBalcon(): ?int
    {
        return $this->surfaceBalcon;
    }

    /**
     * @param int $surfaceBalcon
     */
    public function setSurfaceBalcon(?int $surfaceBalcon): self
    {
        $this->surfaceBalcon = $surfaceBalcon;

        return $this;
    }

    /**
     * @return array
     */
    public function getInterior(): ?array
    {
        return $this->interior;
    }

    /**
     * @param array $interior
     */
    public function setInterior(?array $interior): self
    {
        $this->interior = $interior;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateConstruction(): ?string
    {
        return $this->dateConstruction;
    }

    /**
     * @param string $dateConstruction
     */
    public function setDateConstruction(?string $dateConstruction): self
    {
        $this->dateConstruction = $dateConstruction;

        return $this;
    }

    /**
     * @return string
     */
    public function getSituation(): ?string
    {
        return $this->situation;
    }

    /**
     * @param string $situation
     */
    public function setSituation(?string $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * @return string
     */
    public function getStanding(): ?string
    {
        return $this->standing;
    }

    /**
     * @param string $standing
     */
    public function setStanding(?string $standing): self
    {
        $this->standing = $standing;

        return $this;
    }

    /**
     * @return string
     */
    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    /**
     * @param string $cuisine
     */
    public function setCuisine(?string $cuisine): self
    {
        $this->cuisine = $cuisine;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalleManger(): ?string
    {
        return $this->salleManger;
    }

    /**
     * @param string $salleManger
     */
    public function setSalleManger(?string $salleManger): self
    {
        $this->salleManger = $salleManger;

        return $this;
    }

    /**
     * @return array
     */
    public function getExterior(): ?array
    {
        return $this->exterior;
    }

    /**
     * @param array $exterior
     */
    public function setExterior(?array $exterior): self
    {
        $this->exterior = $exterior;

        return $this;
    }

    /**
     * @return int
     */
    public function getNombrePlacard(): ?int
    {
        return $this->nombrePlacard;
    }

    /**
     * @param int $nombrePlacard
     */
    public function setNombrePlacard(?int $nombrePlacard): self
    {
        $this->nombrePlacard = $nombrePlacard;

        return $this;
    }

    /**
     * @return array
     */
    public function getTypeSol(): ?array
    {
        return $this->typeSol;
    }

    /**
     * @param array $typeSol
     */
    public function setTypeSol(?array $typeSol): self
    {
        $this->typeSol = $typeSol;

        return $this;
    }

    /**
     * @return array
     */
    public function getServiceInclus(): ?array
    {
        return $this->serviceInclus;
    }

    /**
     * @param array $serviceInclus
     */
    public function setServiceInclus(?array $serviceInclus): self
    {
        $this->serviceInclus = $serviceInclus;

        return $this;
    }

    /**
     * @return array
     */
    public function getProximite(): ?array
    {
        return $this->proximite;
    }

    /**
     * @param array $proximite
     */
    public function setProximite(?array $proximite): self
    {
        $this->proximite = $proximite;

        return $this;
    }

    /**
     * @return string
     */
    public function getStateGenerale(): ?string
    {
        return $this->stateGenerale;
    }

    /**
     * @param string $stateGenerale
     */
    public function setStateGenerale(?string $stateGenerale): self
    {
        $this->stateGenerale = $stateGenerale;

        return $this;
    }

    /**
     * @return array
     */
    public function getFacade(): ?array
    {
        return $this->facade;
    }

    /**
     * @param array $facade
     */
    public function setFacade(?array $facade): self
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return array
     */
    public function getToiture(): ?array
    {
        return $this->toiture;
    }

    /**
     * @param array $toiture
     */
    public function setToiture(?array $toiture): self
    {
        $this->toiture = $toiture;
        
        return $this;
    }
}



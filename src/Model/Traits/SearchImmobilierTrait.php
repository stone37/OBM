<?php

namespace App\Model\Traits;

trait SearchImmobilierTrait
{
    /**
     * @var int|null
     */
    private $surfaceMin;

    /**
     * @var int|null
     */
    private $surfaceMax;

    /**
     * @var string|null
     */
    private $nbrePiece;

    /**
     * @var string|null
     */
    private $immobilierState;

    /**
     * @var int|null
     */
    private $nbreChambre;

    /**
     * @var int|null
     */
    private $nbreSalleBain;

    /**
     * @var array|null
     */
    private $immobilierAcces;

    /**
     * @var array|null
     */
    private $proximite;

    /**
     * @var array|null
     */
    private $interior;

    /**
     * @var array|null
     */
    private $exterior;

    /**
     * @return int|null
     */
    public function getSurfaceMin(): ?int
    {
        return $this->surfaceMin;
    }

    /**
     * @param int|null $surfaceMin
     */
    public function setSurfaceMin(?int $surfaceMin): self
    {
        $this->surfaceMin = $surfaceMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getSurfaceMax(): ?int
    {
        return $this->surfaceMax;
    }

    /**
     * @param int|null $surfaceMax
     */
    public function setSurfaceMax(?int $surfaceMax): self
    {
        $this->surfaceMax = $surfaceMax;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNbrePiece(): ?string
    {
        return $this->nbrePiece;
    }

    /**
     * @param string|null $nbrePiece
     */
    public function setNbrePiece(?string $nbrePiece): self
    {
        $this->nbrePiece = $nbrePiece;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImmobilierState(): ?string
    {
        return $this->immobilierState;
    }

    /**
     * @param string|null $immobilierState
     */
    public function setImmobilierState(?string $immobilierState): self
    {
        $this->immobilierState = $immobilierState;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbreChambre(): ?int
    {
        return $this->nbreChambre;
    }

    /**
     * @param int|null $nbreChambre
     */
    public function setNbreChambre(?int $nbreChambre): self
    {
        $this->nbreChambre = $nbreChambre;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbreSalleBain(): ?int
    {
        return $this->nbreSalleBain;
    }

    /**
     * @param int|null $nbreSalleBain
     */
    public function setNbreSalleBain(?int $nbreSalleBain): self
    {
        $this->nbreSalleBain = $nbreSalleBain;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInterior(): ?array
    {
        return $this->interior;
    }

    /**
     * @param array|null $interior
     */
    public function setInterior(?array $interior): self
    {
        $this->interior = $interior;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getImmobilierAcces(): ?array
    {
        return $this->immobilierAcces;
    }

    /**
     * @param array|null $immobilierAcces
     */
    public function setImmobilierAcces(?array $immobilierAcces): self
    {
        $this->immobilierAcces = $immobilierAcces;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getProximite(): ?array
    {
        return $this->proximite;
    }

    /**
     * @param array|null $proximite
     */
    public function setProximite(?array $proximite): self
    {
        $this->proximite = $proximite;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getExterior(): ?array
    {
        return $this->exterior;
    }

    /**
     * @param array|null $exterior
     */
    public function setExterior(?array $exterior): self
    {
        $this->exterior = $exterior;

        return $this;
    }
}



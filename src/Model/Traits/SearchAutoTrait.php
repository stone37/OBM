<?php

namespace App\Model\Traits;

trait SearchAutoTrait
{
    /**
     * @var string|null
     */
    private $marque;

    /**
     * @var string|null
     */
    private $model;

    /**
     * @var integer|null
     */
    private $autoYearMin;

    /**
     * @var integer|null
     */
    private $autoYearMax;

    /**
     * @var integer|null
     */
    private $kiloMin;

    /**
     * @var integer|null
     */
    private $kiloMax;

    /**
     * @var string|null
     */
    private $typeCarburant;

    /**
     * @return string|null
     */
    public function getMarque(): ?string
    {
        return $this->marque;
    }

    /**
     * @param string|null $marque
     */
    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string|null $model
     */
    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAutoYearMin(): ?int
    {
        return $this->autoYearMin;
    }

    /**
     * @param int|null $autoYearMin
     */
    public function setAutoYearMin(?int $autoYearMin): self
    {
        $this->autoYearMin = $autoYearMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAutoYearMax(): ?int
    {
        return $this->autoYearMax;
    }

    /**
     * @param int|null $autoYearMax
     */
    public function setAutoYearMax(?int $autoYearMax): self
    {
        $this->autoYearMax = $autoYearMax;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getKiloMin(): ?int
    {
        return $this->kiloMin;
    }

    /**
     * @param int|null $kiloMin
     */
    public function setKiloMin(?int $kiloMin): self
    {
        $this->kiloMin = $kiloMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getKiloMax(): ?int
    {
        return $this->kiloMax;
    }

    /**
     * @param int|null $kiloMax
     */
    public function setKiloMax(?int $kiloMax): self
    {
        $this->kiloMax = $kiloMax;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTypeCarburant(): ?string
    {
        return $this->typeCarburant;
    }

    /**
     * @param string|null $typeCarburant
     */
    public function setTypeCarburant(?string $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }
}



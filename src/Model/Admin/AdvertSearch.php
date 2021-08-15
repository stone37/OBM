<?php

namespace App\Model\Admin;

class AdvertSearch
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $subCategory;

    /**
     * @var string
     */
    private $subDivision;

    /**
     * @var string
     */
    private $city;

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
    public function getSubDivision(): ?string
    {
        return $this->subDivision;
    }

    /**
     * @param string $subDivision
     */
    public function setSubDivision(?string $subDivision): self
    {
        $this->subDivision = $subDivision;

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
}


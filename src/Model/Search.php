<?php

namespace App\Model;

use App\Entity\User;
use App\Model\Traits\SearchAcheterTrait;
use App\Model\Traits\SearchAutoTrait;
use App\Model\Traits\SearchImmobilierTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class Search
{
    use SearchAutoTrait;
    use SearchImmobilierTrait;
    use SearchAcheterTrait;

    /**
     * @var string|null
     */
    private $data;

    /**
     * @var string|null
     */
    private $category;

    /**
     * @var string|null
     */
    private $subCategory;

    /**
     * @var string|null
     */
    private $subDivision;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $zone;

    /**
     * @var bool|null
     */
    private $urgent;

    /**
     * @var int|null
     */
    private $priceMin;

    /**
     * @var int|null
     */
    private $priceMax;

    /**
     * @var string|null
     */
    private $order;

    /**
     * @var string
     */
    private $title;

    /**
     * @var User|UserInterface
     */
    private $user;

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     */
    public function setData(?string $data): self
    {
        $this->data = $data;
        
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubCategory(): ?string
    {
        return $this->subCategory;
    }

    /**
     * @param string|null $subCategory
     */
    public function setSubCategory(?string $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubDivision(): ?string
    {
        return $this->subDivision;
    }

    /**
     * @param string|null $subDivision
     */
    public function setSubDivision(?string $subDivision): self
    {
        $this->subDivision = $subDivision;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getUrgent(): ?bool
    {
        return $this->urgent;
    }

    /**
     * @param bool|null $urgent
     */
    public function setUrgent(?bool $urgent): self
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriceMin(): ?int
    {
        return $this->priceMin;
    }

    /**
     * @param int|null $priceMin
     */
    public function setPriceMin(?int $priceMin): self
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPriceMax(): ?int
    {
        return $this->priceMax;
    }

    /**
     * @param int|null $priceMax
     */
    public function setPriceMax(?int $priceMax): self
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    /**
     * @param string|null $order
     */
    public function setOrder(?string $order): self
    {
        $this->order = $order;

        return $this;
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
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|UserInterface $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZone(): ?string
    {
        return $this->zone;
    }

    /**
     * @param string|null $zone
     */
    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}



<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\CategoryPremiumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CategoryPremium
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=CategoryPremiumRepository::class)
 * @ORM\MappedSuperclass
 */
class CategoryPremium
{
    use IdTrait;
    use PositionTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var Category
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Add category
     *
     * @param Category $category
     */
    public function addCategories(Category $category): self
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Category $category
     */
    public function removeCategories(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }
}

<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AlertRepository::class)
 */
class Alert
{
    use IdTrait;
    use TimestampableTrait;
    use EnabledTrait;

    /**
     * @var Category
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $category;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $subCategory;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private $subDivision;

    /**
     * @var User|UserInterface
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alerts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Category
     */
    public function getSubCategory(): ?Category
    {
        return $this->subCategory;
    }

    /**
     * @param Category $subCategory
     */
    public function setSubCategory(?Category $subCategory): self
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * @return Category
     */
    public function getSubDivision(): ?Category
    {
        return $this->subDivision;
    }

    /**
     * @param Category $subDivision
     */
    public function setSubDivision(?Category $subDivision): self
    {
        $this->subDivision = $subDivision;

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
    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addAlert($this);

        return $this;
    }
}


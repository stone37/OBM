<?php

namespace App\Entity;

use App\Entity\Traits\CategoryTreeTrait;
use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\HelpCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\IdTrait;


/**
 * Class HelpCategory
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=HelpCategoryRepository::class)
 * @Gedmo\Tree(type="nested")
 * @ORM\MappedSuperclass
 */
class HelpCategory
{
    use IdTrait;
    use PositionTrait;
    use EnabledTrait;
    use TimestampableTrait;
    use CategoryTreeTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @var HelpCategory
     *
     * @ORM\OneToMany(targetEntity=HelpCategory::class, mappedBy="parent", cascade={"ALL"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, unique=true)
     *
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"}, unique=true)
     * @Gedmo\SlugHandler(class="Gedmo\Sluggable\Handler\TreeSlugHandler")
     * @Gedmo\SlugHandlerOption(name="parentRelationField", value="parent")
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $permalink;

    /**
     * @var HelpCategory
     *
     * @Gedmo\TreeParent()
     * @Gedmo\SortableGroup()
     *
     * @ORM\ManyToOne(targetEntity=HelpCategory::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var Help
     *
     * @ORM\OneToMany(targetEntity=Help::class, mappedBy="category")
     */
    private $helps;

    public function __construct()
    {
        $this->enabled = true;
        $this->children = new ArrayCollection();
        $this->helps = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Add child
     *
     * @param HelpCategory
     */
    public function addChildren(HelpCategory $children): self
    {
        if(!$this->hasChildren($children)) {
            $this->setParent($this);
            $this->children[] = $children;
        }

        return $this;
    }

    /**
     * Remove child
     *
     * @param HelpCategory $children
     */
    public function removeChildren(HelpCategory $children): self
    {
        if ($this->children->removeElement($children)) {
            $this->setParent(null);
        }

        return $this;
    }

    /**
     * @param HelpCategory $children
     *
     * @return bool
     */
    public function hasChildren(HelpCategory $children): ?bool
    {
        return $this->children->contains($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection|Category
     */
    public function getChildren(): ?Collection
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param HelpCategory $parent
     */
    public function setParent(?HelpCategory $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return HelpCategory
     */
    public function getParent(): ?HelpCategory
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     */
    public function setPermalink(?string $permalink): self
    {
        $this->permalink = $permalink;

        return $this;
    }

    public function getHelps(): ?Collection
    {
        return $this->helps;
    }

    public function addHelp(Help $help): self
    {
        if (!$this->helps->contains($help)) {
            $this->helps[] = $help;
        }

        return $this;
    }

    public function removeHelp(Help $help): self
    {
        if ($this->helps->contains($help)) {
            $this->helps->removeElement($help);
        }

        return $this;
    }

    public function __toString(): ?string
    {
        return (string) $this->getName();
    }
}

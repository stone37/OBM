<?php

namespace App\Entity\Traits;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Trait CategoryTrait
 * @package App\Entity\Traits
 */
trait CategoryTrait
{
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
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $icon;

    /**
     * @var Category
     *
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent", cascade={"ALL"}, orphanRemoval=true)
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
     * @var Category
     *
     * @Gedmo\TreeParent()
     * @Gedmo\SortableGroup()
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     */
    private $types;

    /**
     * @var File
     *
     * @Assert\File(maxSize="10M")
     *
     * @Vich\UploadableField(
     *     mapping="category",
     *     fileNameProperty="fileName",
     *     size="fileSize",
     *     mimeType="fileMimeType",
     *     originalName="fileOriginalName"
     * )
     */
    private $file;

    public function __constructCategory()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param null|string $name
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param null|string $slug
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Get description
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param null|string $description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Set permalink
     *
     * @param null|string $permalink
     */
    public function setPermalink(?string $permalink): self
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * Get permalink
     *
     * @return null|string
     */
    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    /**
     * Add child
     *
     * @param Category
     */
    public function addChildren(Category $children): self
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
     * @param Category $children
     */
    public function removeChildren(Category $children): self
    {
        if ($this->children->removeElement($children)) {
            $this->setParent(null);
        }

        return $this;
    }

    /**
     * @param Category $children
     *
     * @return bool
     */
    public function hasChildren(Category $children): ?bool
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
     * @param Category $parent
     */
    public function setParent(?Category $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Category
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    /**
     * @param array $types
     */
    public function setTypes(?array $types): self
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File $image
     */
    public function setFile(?File $image = null): self
    {
        $this->file = $image;

        if (null !== $image) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    /**
     * Return the Category name
     *
     * @return string
     */
    public function __toString(): ?string
    {
        return (string) $this->getName();
    }

    public function getChildrenNumber()
    {
        return count($this->getChildren());
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->name,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->name,
        ] = unserialize($serialized);
    }
}



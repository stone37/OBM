<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class City
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\MappedSuperclass
 */
class City
{
    use IdTrait;
    use EnabledTrait;
    use TimestampableTrait;
    use PositionTrait;

    /**
     * @var string
     *
     * @Groups({"read:city"})
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="100")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Groups({"read:city"})
     *
     * @Gedmo\Slug(fields={"name"}, unique=true)
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * @var Zone
     *
     * @Groups({"read:city"})
     *
     * @Assert\Valid()
     *
     * @ORM\OneToMany(targetEntity=Zone::class, mappedBy="city", cascade={"ALL"}, orphanRemoval=true)
     */
    private $zones;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lng = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lat = null;

    /**
     * @var string
     *
     * @Groups({"read:city"})
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $countryCode;

    public function __construct()
    {
        $this->zones = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
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
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Collection|Zone[]
     */
    public function getZones(): ?Collection
    {
        return $this->zones;
    }

    /**
     * @param Zone $zone
     * @return $this
     */
    public function addZone(Zone $zone): self
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
        }

        return $this;
    }

    public function removeZone(Zone $zone): self
    {
        if ($this->zones->contains($zone)) {
            $this->zones->removeElement($zone);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLat(): ?string
    {
        return $this->lat;
    }

    /**
     * @param string $lat
     */
    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return string
     */
    public function getLng(): ?string
    {
        return $this->lng;
    }

    /**
     * @param string $lng
     */
    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function __toString(): ?string
    {
        return (string) $this->getName();
    }
}

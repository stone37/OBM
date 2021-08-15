<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=FavoriteRepository::class)
 */
class Favorite
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(User::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Advert
     *
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

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
        $user->addFavorite($this);

        return $this;
    }

    /**
     * @return Advert
     */
    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    /**
     * @param Advert $advert
     */
    public function setAdvert(Advert $advert): self
    {
        $this->advert = $advert;
        $advert->addFavorite($this);

        return $this;
    }
}

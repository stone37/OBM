<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Données pour la mise à jour du profil utilisateur.
 *
 * @Unique(entityClass="App\Entity\User", field="email")
 * @Unique(entityClass="App\Entity\User", field="phone")
 * @Unique(entityClass="App\Entity\User", field="username")
 */
class ProfileUpdateDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=100)
     * @Assert\Email()
     */
    public $email;

    public $username = '';

    /**
     * @var string
     *
     * @Assert\NotBlank(normalizer="trim", message="Entrez un prénom s'il vous plait.")
     * @Assert\Length(
     *     min="2", max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long."
     * )
     */
    public $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Entrez un prénom s'il vous plait.")
     *
     * @Assert\Length(
     *     min="2", max="180",
     *     minMessage="Le prénom est trop court.",
     *     maxMessage="Le prénom est trop long.")
     */
    public $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Entrez un numéro de téléphone s''il vous plait.")
     * @Assert\Length(
     *     min="10", max="180",
     *     minMessage="Le numéro de téléphone est trop court.",
     *     maxMessage="Le numéro de téléphone est trop long."
     * )
     */
    public $phone;

    /**
     * @var bool
     */
    public $phoneStatus;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $city;

    public $user;

    public function __construct(User $user)
    {
        $this->email = $user->getEmail();
        $this->username = $user->getUsername();
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->phone = $user->getPhone();
        $this->phoneStatus = $user->isPhoneStatus();
        $this->address = $user->getAddress();
        $this->city = $user->getCity();

        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->user->getId() ?: 0;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username ?: '';

        return $this;
    }
}

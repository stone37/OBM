<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportRepository"))
 * @Assert\Expression(
 *     expression="this.getAdvert() !== null",
 *     message="Un signalement doit être associé à une annonce ou un message"
 * )
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id = null;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Veuillez renseigne votre email")
     * @Assert\Email(message="Votre email n'est pas valide")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email = null;

    /**
     * @ORM\ManyToOne(targetEntity=Advert::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $advert = null;

    /**
     * @var string
     *
     * @Assert\NotBlank(normalizer="trim", message="Veuillez renseigne la raison")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $reason;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var
     *
     * @Assert\NotBlank(message="Merci de nous préciser les détails de l'annonce vous amenant à signaler cette annonce")
     * @Assert\Length(min=10, minMessage="Votre message doit contenir au moin {{ limit }} caractères")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): Report
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 *
 * @ORM\Table(name="review")
 */
class Review
{
    const REVIEW = 'review';
    const SUBJECT = 'subjection';

    use EnabledTrait;
    use TimestampableTrait;
    use PositionTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="50")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Assert\NotBlank(groups="Suggestion")
     * @Assert\Length(min="2", max="50", groups="Suggestion")
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="8")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var integer
     *
     * @Assert\NotBlank(groups="Review")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $type = self::REVIEW;

    public function __construct()
    {
        $this->enabled = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

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
     * @return int
     */
    public function getNote(): ?int
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AdvertPictureRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class AdvertPicture
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AdvertPictureRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass
 */
class AdvertPicture
{
    use IdTrait;
    use TimestampableTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $principale;

    /**
     * @var File
     *
     * @Assert\File(
     *     maxSize="8000k",
     *     maxSizeMessage="Le fichier excède 8000Ko",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *     mimeTypesMessage= "Format non autorisés. Formats autorisés: png, jpeg, jpg, gif"
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tempFilename;

    /**
     * @var Advert
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=Advert::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advert;

    /**
     * @return File
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->extension) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->extension;

            // On réinitialise les valeurs des attributs url et alt
            $this->extension = null;
            $this->name = null;
        }

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload(): void
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {return;}

        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->extension = $this->file->guessExtension();

        // Et on génère l'attribut alt de la balise <img>, à la valeur du nom du fichier sur le PC de l'internaute
        $this->name = $this->file->getFilename();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(): void
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->file) {return;}

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->id . '.' . $this->extension   // Le nom du fichier à créer, ici « id.extension »
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(): self
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->extension;

        return $this;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): self
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }

        return $this;
    }

    /**
     * Retourne le chemin relatif vers l'image pour un navigateur
     *
     * @return string
     */
    public function getUploadDir(): string
    {
        return 'uploads/images/adverts';
    }

    /**
     * Retourne le chemin relatif vers l'image pour le code PHP
     *
     * @return string
     */
    protected function getUploadRootDir(): string
    {
        return __DIR__ . '/../../public/' . $this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir() . '/' . $this->getId() . '.' . $this->getExtension();
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
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPrincipale(): ?bool
    {
        return $this->principale;
    }

    /**
     * @param bool $principale
     */
    public function setPrincipale(bool $principale): self
    {
        $this->principale = $principale;

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
    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }
}

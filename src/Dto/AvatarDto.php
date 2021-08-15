<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AvatarDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Image(mimeTypes={"image/jpeg", "image/png"}, minWidth=110, maxHeight=1400, maxWidth=1400, minHeight=110)
     */
    public $file;

    /**
     * @var User
     */
    public $user;

    public function __construct(UploadedFile $file, User $user)
    {
        $this->file = $file;
        $this->user = $user;
    }
}

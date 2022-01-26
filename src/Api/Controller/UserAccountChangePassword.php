<?php

namespace App\Api\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAccountChangePassword extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $data
     * @return User
     * @throws \Exception
     */
    public function __invoke($data): ?User
    {
        if ($data->getPlainPassword()) {
            $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPlainPassword()));

            $data->eraseCredentials();
        }

        return $data;
    }
}



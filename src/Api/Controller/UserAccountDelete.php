<?php

namespace App\Api\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAccountDelete extends AbstractController
{
    public const DAYS = 5;

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
    public function __invoke($data)
    {
        if ($this->passwordEncoder->isPasswordValid($data, $data->getPlainPassword() ?? '')) {

            $data->setDeleteAt(new DateTimeImmutable('+ '. self::DAYS .' days'));

            $data->eraseCredentials();
        }

        return $data;
    }
}



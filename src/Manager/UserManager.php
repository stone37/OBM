<?php

namespace App\Manager;

use App\Entity\Invitation;
use App\Entity\User;
use App\Service\TokenGeneratorService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    private $bag;
    private $url;
    private $passwordEncoder;
    private $token;

    public function __construct(
        EntityManagerInterface $em, 
        FlashBagInterface $bag, 
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGeneratorService $tokenGenerator
    )
    {
        $this->em = $em;
        $this->bag = $bag;
        $this->url = $urlGenerator;
        $this->passwordEncoder = $passwordEncoder;
        $this->token = $tokenGenerator;
    }

    /**
     * @param string|null $code
     * @return User|RedirectResponse
     */
    public function createUser(Request $request)
    {
        $user = new User();

        if ($request->query->has('code')) {
            try {
                $invitation = $this->verifyCode($request->query->get('code'));
            } catch (Exception $e) {
                $this->bag->add('error', $e->getMessage());

                return new RedirectResponse($this->url->generate('app_register'));
            }

            $user->setInvitation($invitation);
        }

        return $user;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return User
     */
    public function generateApiUser(User $user, Request $request): User
    {
        $isOauthUser = false;

        if ($user->getPlainPassword()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));

            $user->eraseCredentials();
        }

        if ($request->getMethod() === 'POST' && !$user->getId()) {
            $user->setConfirmationToken($isOauthUser ? null : $this->token->generate(60));
            $user->setNotificationsReadAt(new DateTimeImmutable());
        } else {
            $user->setUpdatedAt(new DateTime());
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     * @param bool $andFlush
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        $this->em->persist($user);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * @return \Doctrine\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(User::class);
    }

    /**
     * @param UserInterface $user
     */
    public function deleteUser(UserInterface $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @param $code
     * @return Invitation|null
     * @throws Exception
     */
    private function verifyCode(string $code): ?Invitation
    {
        $invitation = $this->em->getRepository(Invitation::class)->findOneByCode($code);

        if (!$invitation) {
            throw new Exception("Code d'invitation erroné.");
        }

        return $invitation;
    }
}
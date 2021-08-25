<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\Settings;
use App\Entity\User;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class InvitationController extends AbstractController
{
    /**
     * @var Settings
     */
    private $settings;

    public function __construct(SettingsManager $manager) {
        $this->settings = $manager->get();
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(EntityManagerInterface $em): Response
    {
        if (!$this->settings->isActiveParrainage()) throw $this->createNotFoundException('Page introuvable');

        $user = $this->getUserOrThrow();
        $invitation = $em->getRepository(Invitation::class)->findOneByUser($user);

        if (null === $invitation) {
            $invitation = (new Invitation())->setOwner($user);

            $em->persist($invitation);
            $em->flush();
        }

        return $this->render('user/invitation/index.html.twig', [
            'user' => $user,
            'invitation' => $invitation,
            'settings' => $this->settings
        ]);
    }

    /**
     * @return User
     */
    private function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}

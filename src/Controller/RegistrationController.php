<?php

namespace App\Controller;

use App\Auth\Authenticator;
use App\Entity\User;
use App\Entity\Wallet;
use App\Event\UserCreatedEvent;
use App\Form\RegistrationFormType;
use App\Manager\UserManager;
use App\Security\EmailVerifier;
use App\Service\SocialLoginService;
use App\Service\TokenGeneratorService;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $manager;

    /**
     * RegistrationController constructor.
     *
     * @param EmailVerifier $emailVerifier
     * @param UserManager $manager
     */
    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @param SocialLoginService $socialLoginService
     * @param TokenGeneratorService $tokenGenerator
     * @param EventDispatcherInterface $dispatcher
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param Authenticator $authenticator
     * @return Response
     * @throws \Exception
     */
    public function register(
        Request $request,
        SocialLoginService $socialLoginService,
        TokenGeneratorService $tokenGenerator,
        EventDispatcherInterface $dispatcher,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        Authenticator $authenticator): Response
    {
        // Si l'utilisateur est connecté, on le redirige vers la home
        $loggedInUser = $this->getUser();

        if ($loggedInUser) {
            return $this->redirectToRoute('app_dashboard_index');
        }

        $user = $this->manager->createUser();

        $rootErrors = [];
        // Si l'utilisateur provient de l'oauth, on préremplit ses données
        $isOauthUser = $request->get('oauth') ? $socialLoginService->hydrate($user) : false;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $form->has('plainPassword') ? $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ) : ''
            );
            $user->setCreatedAt(new DateTime());
            $user->setConfirmationToken($isOauthUser ? null : $tokenGenerator->generate(60));
            $user->setNotificationsReadAt(new DateTimeImmutable());
            $user->setWallet(new Wallet());

            $this->manager->updateUser($user);

            $dispatcher->dispatch(new UserCreatedEvent($user, $isOauthUser));

            if ($isOauthUser) {
                $this->addFlash(
                    'success',
                    'Votre compte a été créé avec succès'
                );

                return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main'
                ) ?: $this->redirectToRoute('app_user_profil_edit');
            }

            $this->addFlash(
                'success',
                'Un message avec un lien de confirmation vous a été envoyé par mail. Veuillez suivre ce lien pour activer votre compte.'
            );

            return $this->redirectToRoute('app_login');
        } elseif ($form->isSubmitted()) {
            /** @var FormError $error */
            foreach ($form->getErrors() as $error) {
                if (null === $error->getCause()) {
                    $rootErrors[] = $error;
                }
            }
        }

        return $this->render('site/registration/register.html.twig', [
            'form' => $form->createView(),
            'errors' => $rootErrors,
            'menu' => 'register',
            'oauth_registration' => $request->get('oauth'),
            'oauth_type' => $socialLoginService->getOauthType(),
        ]);
    }

    /**
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function confirmToken(
        User $user,
        Request $request,
        EntityManagerInterface $em): RedirectResponse
    {
        $token = $request->get('token');

        if (empty($token) || $token !== $user->getConfirmationToken()) {
            $this->addFlash('error', "Ce token n'est pas valide");

            return $this->redirectToRoute('app_register');
        }

        if ($user->getCreatedAt() < new DateTime('-2 hours')) {
            $this->addFlash('error', 'Ce token a expiré');

            return $this->redirectToRoute('app_register');
        }

        $user->setConfirmationToken(null);
        $em->flush();

        $this->addFlash('success', 'Votre compte a été validé.');

        return $this->redirectToRoute('app_login');
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Entity\User;
use App\Mailing\Mailer;
use App\Repository\AdvertRepository;
use App\Repository\PaymentRepository;
use App\Repository\ReportRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use App\Service\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DashboardController extends AbstractController
{
    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    public function index(
        AdvertRepository $advertRepository,
        PaymentRepository $paymentRepository,
        UserRepository $userRepository,
        ReviewRepository $reviewRepository,
        ReportRepository $reportRepository)
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'settings' => $this->settings,
            'adValide' => $advertRepository->getValideNumber(),
            'adNotValide' => $advertRepository->getNotValideNumber(),
            'adDeleted' => $advertRepository->getDeletedNumber(),
            'adExpired' => $advertRepository->getExpiredNumber(),
            'users' => $userRepository->getUserNumber(),
            'lastClients' => $userRepository->getLastClients(),
            'lastOrders' => $paymentRepository->getLasts(),
            'months' => $paymentRepository->getMonthlyRevenues(),
            'days' => $paymentRepository->getDailyRevenues(),
            'reports' => $reportRepository->findBy([], ['createdAt' => 'desc']),
            'orders' => $paymentRepository->getNumber(),
            'reviews' => $reviewRepository->getNumber(Review::REVIEW),
            'suggestions' => $reviewRepository->getNumber(Review::SUBJECT),
        ]);
    }

    /**
     * Envoie un email de test à mail-tester pour vérifier la configuration du serveur.
     */
    public function testMail(Request $request, Mailer $mailer): RedirectResponse
    {
        $email = $mailer->createEmail('mails/auth/register.twig', [
            'user' => $this->getUserOrThrow(),
        ])
            ->to($request->get('email'))
            ->subject('O\'blackmarket | Confirmation du compte');
        $mailer->sendNow($email);

        $this->addFlash('success', "L'email de test a bien été envoyé");

        return $this->redirectToRoute('app_admin_dashboard');
    }

    private function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}


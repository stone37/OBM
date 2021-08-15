<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Report;
use App\Mailing\Mailer;
use App\Service\SettingsManager;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdvertReportController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param Advert $advert
     * @param Mailer $mailer
     * @return JsonResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        Advert $advert,
        Mailer $mailer
    )
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $report = (new Report())
            ->setCreatedAt(new DateTime())
            ->setAdvert($advert)
            ->setContent($request->request->get('reportContent') ?? null)
            ->setReason($request->request->get('reason')?? null);

        if ($this->getUser()) {
            $report->setEmail($this->getUser()->getEmail());
        } else {
            $report->setEmail($request->request->get('reportEmail'));
        }

        $errors = $validator->validate($report);

        if (!$this->isCsrfTokenValid('advert-report', $request->request->get('_token'))) {
            $errors[] = 'Le jeton CSRF est invalide.';
        }

        if (!count($errors)) {
            $em->persist($report);
            $em->flush();

            $sender = $mailer->createEmail('mails/advert/report.twig', [
                'data' => $report,
            ])->to($this->settings->getEmail())
                ->subject($this->settings->getName().' | Signalement d\'une annonce');

            $mailer->send($sender);

            return new JsonResponse([
                'success' => true,
                'message' => 'Merci pour votre signalement',
            ]);
        }

        $data = [];

        foreach ($errors as $error) {
            $data[] = $error->getMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($data)]);
    }
}


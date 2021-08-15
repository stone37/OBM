<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\NewsletterType;
use App\Mailing\Mailer;
use App\Service\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class NewsletterController extends AbstractController
{
    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    public function index(Request $request, Mailer $mailer, EntityManagerInterface $em): Response
    {
        $emails = $em->getRepository(User::class)->findAdEmails();

        $form = $this->createForm(NewsletterType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            foreach ($emails as $data) {
                $sender = $mailer->createEmail('mails/newsletter/advertiser.twig', [
                    'data' => $formData,
                ])->to($data['email'])
                    ->subject($this->settings->getName().' | '.$formData['subject']);

                $mailer->send($sender);
            }

            $this->addFlash('info', 'Votre newsletter a été envoyée avec succès');

            return $this->redirectToRoute('app_admin_ad_newsletter');
        }

        return $this->render('admin/newsletter/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function user(Request $request, Mailer $mailer, EntityManagerInterface $em): Response
    {
        $emails = $em->getRepository(User::class)->findEmails();

        $form = $this->createForm(NewsletterType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            foreach ($emails as $data) {
                $sender = $mailer->createEmail('mails/newsletter/user.twig', [
                    'data' => $formData,
                ])->to($data['email'])
                    ->subject($this->settings->getName().' | '.$formData['subject']);

                $mailer->send($sender);
            }

            $this->addFlash('info', 'Votre newsletter a été envoyée avec succès');

            return $this->redirectToRoute('app_admin_user_newsletter');
        }

        return $this->render('admin/newsletter/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function pro(Request $request, Mailer $mailer, EntityManagerInterface $em)
    {
        $emails = $em->getRepository(User::class)->findProEmails();

        $form = $this->createForm(NewsletterType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            foreach ($emails as $data) {
                $sender = $mailer->createEmail('mails/newsletter/pro.twig', [
                    'data' => $formData,
                ])->to($data['email'])
                    ->subject($this->settings->getName().' | '.$formData['subject']);

                $mailer->send($sender);
            }

            $this->addFlash('info', 'Votre newsletter a été envoyée avec succès');

            return $this->redirectToRoute('app_admin_user_pro_newsletter');
        }

        return $this->render('admin/newsletter/pro.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


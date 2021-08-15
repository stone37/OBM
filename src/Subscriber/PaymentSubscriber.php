<?php

namespace App\Subscriber;

use App\Entity\Settings;
use App\Event\PaymentEvent;
use App\Mailing\Mailer;
use App\Manager\SettingsManager;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaymentSubscriber implements EventSubscriberInterface
{
    private $mailer;

    /** @var Settings */
    private $settings;

    private $service;

    public function __construct(
        Mailer $mailer,
        SettingsManager $manager,
        EntityManagerInterface $em,
        NotificationService $service
    )
    {
        $this->mailer = $mailer;
        $this->settings = $manager->get();
        $this->service = $service;
        $this->em = $em;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEvent::class => 'onPayment',
        ];
    }

    /**
     * @param PaymentEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onPayment(PaymentEvent $event)
    {
        $user = $event->getOrder()->getUser();

        $email = $this->mailer->createEmail('mails/order/validate.twig', [
            'order' => $event->getOrder(),
        ])
            ->to($event->getOrder()->getUser()->getEmail())
            ->subject($this->settings->getName().' | Validation de votre commande');

        $this->mailer->send($email);

        /*$wording = 'Votre achat a bien été valider';
        $this->service->notifyUser($user, $wording, $event->getPayment());*/
    }



}

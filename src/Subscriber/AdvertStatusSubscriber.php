<?php

namespace App\Subscriber;

use App\Entity\Advert;
use App\Entity\Settings;
use App\Event\AdminCRUDEvent;
use App\Event\AdvertCreateEvent;
use App\Event\AdvertDeniedEvent;
use App\Event\AdvertValidateEvent;
use App\Mailing\Mailer;
use App\Manager\SettingsManager;
use App\Service\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvertStatusSubscriber implements EventSubscriberInterface
{
    /** @var Settings */
    private $settings;
    private $mailer;
    private $service;
    private $session;

    public function __construct(
        Mailer $mailer,
        SettingsManager $manager,
        SessionInterface $session,
        NotificationService $service)
    {
        $this->mailer = $mailer;
        $this->settings = $manager->get();
        $this->service = $service;
        $this->session = $session;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AdvertValidateEvent::class => 'onValidate',
            AdvertDeniedEvent::class => 'onDenied',
            AdvertCreateEvent::class => 'onCreated',
            AdminCRUDEvent::POST_DELETE => 'onDeleted',
        ];
    }

    /**
     * @param AdvertValidateEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onValidate(AdvertValidateEvent $event): void
    {
        /** @var Advert $advert */
        $advert = $event->getAdvert();

        $email = $this->mailer->createEmail('mails/advert/validate.twig', [
            'advert' => $advert,
        ])
            ->to($event->getAdvert()->getUser()->getEmail())
            ->subject($this->settings->getName().' | Validation de votre annonce');

        $this->mailer->sendNow($email);

        $userName = $advert->getUser()->getUsername() ?
            htmlentities($advert->getUser()->getUsername()) : htmlentities($advert->getUser()->getFirstName());

        $advertTitle = htmlentities($advert->getTitle());
        $wording = '%s votre annonce %s a été valider';

        $this->service->notifyUser(
            $event->getAdvert()->getUser(),
            sprintf($wording, "<strong>{$userName}</strong>", "« $advertTitle »"),
            $advert
        );
    }

    /**
     * @param AdvertDeniedEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onDenied(AdvertDeniedEvent $event): void
    {
        /** @var Advert $advert */
        $advert = $event->getAdvert();

        $email = $this->mailer->createEmail('mails/advert/denied.twig', [
                'user' => $event->getAdvert()->getUser(),
            ])
            ->to($event->getAdvert()->getUser()->getEmail())
            ->subject($this->settings->getName().' | Rejet de votre annonce');

        $this->mailer->sendNow($email);

        $userName = $advert->getUser()->getUsername() ?
            htmlentities($advert->getUser()->getUsername()) : htmlentities($advert->getUser()->getFirstName());

        $advertTitle = htmlentities($advert->getTitle());
        $wording = '%s votre annonce %s a été rejeter';

        $this->service->notifyUser(
            $event->getAdvert()->getUser(),
            sprintf($wording, "<strong>{$userName}</strong>", "« $advertTitle »"),
            $advert
        );
    }

    /**
     * @param AdvertCreateEvent $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onCreated(AdvertCreateEvent $event): void
    {
        if ($this->session->has('app_cart') && !empty($this->session->get('app_cart'))) {
            $this->session->set('app_advert', $event->getAdvert()->getId());
        }

        $email = $this->mailer->createEmail('mails/advert/create.twig', [
            'user' => $event->getAdvert()->getUser(),
            'advert' => $event->getAdvert(),
        ])
            ->to($event->getAdvert()->getUser()->getEmail())
            ->subject($this->settings->getName().' | Création de votre annonce');

        $this->mailer->sendNow($email);

        $message = "Création d'une nouvelle annonce !<br> <strong>{$event->getAdvert()->getTitle()}</strong>";

        $this->service->notifyChannel("admin", $message, $event->getAdvert());
    }

    /**
     * @param AdminCRUDEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onDeleted(AdminCRUDEvent $event): void
    {
        if (!($event->getEntity() instanceof Advert)) return;

        /** @var Advert $advert */
        $advert = $event->getEntity();

        $email = $this->mailer->createEmail('mails/advert/deleted.twig', [
            'user' => $advert->getUser(),
            ])
            ->to($advert->getUser()->getEmail())
            ->subject($this->settings->getName().' | Suppression de votre annonce');

        $this->mailer->sendNow($email);

        $userName = $advert->getUser()->getUsername() ?
            htmlentities($advert->getUser()->getUsername()) : htmlentities($advert->getUser()->getFirstName());

        $advertTitle = htmlentities($advert->getTitle());
        $wording = '%s votre annonce %s a été supprimer';

        $this->service->notifyUser(
            $advert->getUser(),
            sprintf($wording, "<strong>{$userName}</strong>", "« $advertTitle »"),
            $advert
        );
    }
}

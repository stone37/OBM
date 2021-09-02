<?php

namespace App\Subscriber;

use App\Entity\AdvertPicture;
use App\Entity\AdvertRead;
use App\Entity\Alert;
use App\Entity\Settings;
use App\Event\AdvertBadEvent;
use App\Event\AdvertCreatedInitializeEvent;
use App\Event\AdvertImagePreEditEvent;
use App\Event\AdvertPreCreatedEvent;
use App\Event\AdvertPreEditEvent;
use App\Event\AdvertShowEvent;
use App\Event\AdvertValidateEvent;
use App\Event\MessageCreatedEvent;
use App\Manager\SettingsManager;
use App\Service\OrphanageManager;
use App\Service\UploadService;
use App\Mailing\Mailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class AdvertSubscriber implements EventSubscriberInterface
{
    /**
     * @var UploadService
     */
    private $uploadService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var OrphanageManager
     */
    private $orphanageManager;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $url;

    private $mailer;

    /** @var Settings */
    private $settings;

    public function __construct(
        UploadService $uploadService,
        SessionInterface $session,
        Security $security,
        OrphanageManager $orphanageManager,
        UrlGeneratorInterface $urlGenerator,
        Mailer $mailer,
        SettingsManager $manager,
        EntityManagerInterface $em)
    {
        $this->uploadService = $uploadService;
        $this->session = $session;
        $this->security = $security;
        $this->orphanageManager = $orphanageManager;
        $this->em = $em;
        $this->url = $urlGenerator;
        $this->mailer = $mailer;
        $this->settings = $manager->get();
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AdvertCreatedInitializeEvent::class => 'onInit',
            AdvertPreCreatedEvent::class => 'onUpload',
            AdvertImagePreEditEvent::class => 'onUploadEdit',
            AdvertPreEditEvent::class => 'onEdit',
            AdvertBadEvent::class => 'onError',
            AdvertShowEvent::class => 'onView',
            AdvertValidateEvent::class => 'onValidated',
            MessageCreatedEvent::class => 'onMessageCreated',
        ];
    }

    /**
     * @param AdvertCreatedInitializeEvent $event
     */
    public function onInit(AdvertCreatedInitializeEvent $event)
    {
        if (!$event->getRequest()->isMethod('POST')) {

            $this->session->set('app_advert_image', []);
            $this->session->set('app_location_lng', []);
            $this->session->set('app_location_lat', []);
            $this->session->set('app_cart', []);
            $this->session->remove('app_advert');
            $this->session->remove('app_image_pos');

            $this->orphanageManager->initClear($this->session);
        }
    }

    /**
     * @param AdvertPreCreatedEvent $event
     */
    public function onUpload(AdvertPreCreatedEvent $event)
    {
        $advert  = $event->getAdvert();

        $files = $this->uploadService->getFilesUpload($this->session);

        $data = $this->session->get('app_advert_image');
        $principale = [];

        foreach ($data as $values) {
            foreach ($values as $key => $value) {
                $principale[$key] = $value;
            }
        }

        foreach ($files as $file) {
            $image = (new AdvertPicture())
                ->setFile(new File($file->getPathname()))
                ->setPrincipale((bool)$principale[$file->getFilename()]);

            $advert->addImage($image);
        }

        // Latitude et longitude
        if ($this->session->has('app_location_lat') && !empty($this->session->get('app_location_lat'))) {
            $advert->getLocation()->setLat($this->session->get('app_location_lat'));
        }

        if ($this->session->has('app_location_lng') && !empty($this->session->get('app_location_lng'))) {
            $advert->getLocation()->setLng($this->session->get('app_location_lng'));
        }

        // Paiement option
        if ($this->session->has('app_cart') && !empty($this->session->get('app_cart'))) {
            $this->session->set('app_advert', $event->getAdvert()->getId());

            $event->setResponse(new RedirectResponse($this->url->generate('app_cart_validate')));
        }
    }

    /**
     * @param AdvertImagePreEditEvent $event
     */
    public function onUploadEdit(AdvertImagePreEditEvent $event)
    {
        $advert  = $event->getAdvert();
        $files = $this->uploadService->getFilesUpload($this->session);

        foreach ($files as $file) {
            $image = (new AdvertPicture())
                ->setFile(new File($file->getPathname()));

            $this->em->persist($image);

            $advert->addImage($image);
        }
    }

    /**
     * @param AdvertPreEditEvent $event
     */
    public function onEdit(AdvertPreEditEvent $event)
    {
        $advert  = $event->getAdvert();

        // Latitude et longitude
        if ($this->session->has('app_location_lat') && !empty($this->session->get('app_location_lat'))) {
            $advert->getLocation()->setLat($this->session->get('app_location_lat'));
        }

        if ($this->session->has('app_location_lng') && !empty($this->session->get('app_location_lng'))) {
            $advert->getLocation()->setLng($this->session->get('app_location_lng'));
        }

        if (!$this->session->has('app_image_pos')) { return; }
        if (!$advert->getImages()) { return; }

        foreach ($advert->getImages() as $image) {
            if ($image->getId() == $this->session->get('app_image_pos')) {
                $image->setPrincipale(true);
            }
        }
    }

    /**
     * @param AdvertBadEvent $event
     */
    public function onError(AdvertBadEvent $event)
    {
        if ($event->getRequest()->isMethod('POST')) {
            $this->session->set('app_advert_image', []);
            $this->session->set('app_advert_cart', []);
            $this->orphanageManager->initClear($this->session);
        }
    }

    /**
     * @param AdvertShowEvent $event
     */
    public function onView(AdvertShowEvent $event)
    {
        $advert = $event->getAdvert();

        if ($this->security->getUser() === $advert->getUser()) {
            return;
        }

        $read = (new AdvertRead())->setAdvert($advert);
        $this->em->persist($read);
        $this->em->flush();
    }

    /**
     * @param MessageCreatedEvent $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onMessageCreated(MessageCreatedEvent $event): void
    {
        $message = $event->getMessage();

        $advert = $message->getAdvert();
        $advert->setLastMessage($message);
        $advert->setUpdatedAt(new DateTime());
        $advert->setMessageCount($advert->getMessageCount()+1);
        $this->em->flush();

        $email = $this->mailer->createEmail('mails/advert/message.twig', [
            'message' => $message,
        ])->to($advert->getUser()->getEmail())
            ->subject($this->settings->getName().' | Nouveau message de ' . $message->getFirstname());

        $this->mailer->send($email);
    }

    /**
     * @param AdvertValidateEvent $event
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onValidated(AdvertValidateEvent $event)
    {
        $alerts = $this->em->getRepository(Alert::class)->getValide($event->getAdvert());

        if (!$alerts) {
            return;
        }

        /** @var Alert $alert */
        foreach ($alerts as $alert) {
            $email = $this->mailer->createEmail('mails/advert/alert.twig', [
                'advert' => $event->getAdvert(),
                'alert' => $alert,
            ])->to($alert->getUser())
                ->subject($this->settings->getName().' alerts | Dernières annonces '.$event->getAdvert()->getCategory());

            $this->mailer->sendNow($email);
        }
    }
}

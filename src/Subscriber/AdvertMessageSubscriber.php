<?php

namespace App\Subscriber;

use App\Event\MessageCreatedEvent;
use App\Mailing\Mailer;
use App\Manager\AdvertManager;
use App\Manager\SettingsManager;
use App\Service\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdvertMessageSubscriber implements EventSubscriberInterface
{
    private $service;
    private $manager;
    private $mailer;
    private $settings;

    public function __construct(
        NotificationService $service,
        AdvertManager $manager,
        SettingsManager $settingsManager,
        Mailer $mailer
    )
    {
        $this->service = $service;
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->settings = $settingsManager->get();
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MessageCreatedEvent::class => 'onMessageCreated',
        ];
    }

    /**
     * @param MessageCreatedEvent $event
     * @throws \Doctrine\ORM\ORMException
     */
    public function onMessageCreated(MessageCreatedEvent $event): void
    {
        $message = $event->getMessage();
        $advert = $message->getAdvert();

        $userName = htmlentities($message->getFirstname());

        $advertTitle = htmlentities($advert->getTitle());
        $wording = '%s vous a envoyé un message %s';

        $this->service->notifyUser(
            $advert->getUser(),
            sprintf($wording, "<strong>{$userName}</strong>", "« $advertTitle »"),
            $advert
        );

        if ($message->isAdSimilar()) {
            $adverts = $this->manager->getAdvertSimilar($advert);

            if (!$adverts) {
                return;
            }

            $email = $this->mailer->createEmail('mails/advert/similar.twig', [
                'adverts' => $adverts,
                'advert' => $advert,
                'message' => $message,
            ])->to($message->getEmail())
                ->subject($this->settings->getName().' | Annonces similaires '.$advert->getCategory());

            $this->mailer->send($email);
        }
    }

}

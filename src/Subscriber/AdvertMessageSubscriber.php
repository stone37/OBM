<?php

namespace App\Subscriber;

use ApiPlatform\Core\Api\UrlGeneratorInterface;
use App\Entity\ThreadMessage;
use App\Event\MessageCreatedEvent;
use App\Event\ThreadMessageEvent;
use App\Service\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdvertMessageSubscriber implements EventSubscriberInterface
{
    private $service;
    private UrlGeneratorInterface $url;

    public function __construct(NotificationService $service, UrlGeneratorInterface $url)
    {
        $this->service = $service;
        $this->url = $url;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ThreadMessageEvent::class => 'onMessageCreated',
        ];
    }

    /**
     * @param MessageCreatedEvent $event
     * @throws \Doctrine\ORM\ORMException
     */
    public function onMessageCreated(ThreadMessageEvent $event): void
    {
        /** @var ThreadMessage $message */
        $message = $event->getMessage();

        $userName = htmlentities($message->getSender()->getUsername());

        $messageText = htmlentities($message->getBody());
        $wording = '%s vous a envoyé un message %s';

        $user = null;

        foreach($message->getThread()->getParticipants() as $participant) {
            if ($message->getSender()->getId() != $participant->getId()) {
                $user = $participant;
            }
        }

        if ($user == null) {
            return;
        }

        $this->service->notifyUser(
            $user,
            sprintf($wording, "<strong>{$userName}</strong>", "« $messageText »"),
            $message->getThread()->getAdvert(),
            $this->url->generate('app_message_thread_view', ['threadId' => $message->getThread()->getId()])
        );
    }

}

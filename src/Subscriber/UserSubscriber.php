<?php

namespace App\Subscriber;

use App\Event\UserBannedEvent;
use App\Repository\AdvertRepository;
use App\Repository\MessageRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    private $messageRepository;
    private $advertRepository;

    public function __construct(MessageRepository $messageRepository, AdvertRepository $advertRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->advertRepository = $advertRepository;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBannedEvent::class => 'cleanUserContent',
        ];
    }

    /**
     * @param UserBannedEvent $event
     */
    public function cleanUserContent(UserBannedEvent $event): void
    {
        $this->messageRepository->deleteForUser($event->getUser());
        $this->advertRepository->deleteForUser($event->getUser());
    }
}

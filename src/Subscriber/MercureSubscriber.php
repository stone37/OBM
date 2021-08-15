<?php

namespace App\Subscriber;

use App\Entity\User;
use App\Event\NotificationCreatedEvent;
use App\Event\NotificationReadEvent;
use App\Queue\UEnqueueMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class MercureSubscriber implements EventSubscriberInterface
{
    private $serializer;

    private $enqueue;

    private $hub;

    public function __construct(SerializerInterface $serializer, HubInterface $hub, UEnqueueMethod $enqueue)
    {
        $this->serializer = $serializer;
        $this->enqueue = $enqueue;
        $this->hub = $hub;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            //NotificationCreatedEvent::class => 'publishNotification',
            NotificationReadEvent::class => 'onNotificationRead',
        ];
    }

    public function publishNotification(NotificationCreatedEvent $event): void
    {
        $notification = $event->getNotification();
        $channel = $notification->getChannel();
        if ('public' === $channel && $notification->getUser() instanceof User) {
            $channel = 'user/'.$notification->getUser()->getId();
        }
        $update = new Update("/notifications/$channel", $this->serializer->serialize([
            'type' => 'notification',
            'data' => $notification,
        ], 'json', [
            'groups' => ['read:notification'],
            'iri' => false,
        ]), true);

        $this->hub->publish($update);
        $this->enqueue->enqueue(HubInterface::class, 'publish', [$update]);
    }

    public function onNotificationRead(NotificationReadEvent $event): void
    {
        $user = $event->getUser();
        $update = new Update(
            "/notifications/user/{$user->getId()}",
            '{"type": "markAsRead"}',
            true
        );

        $this->enqueue->enqueue(HubInterface::class, 'publish', [$update]);
    }
}


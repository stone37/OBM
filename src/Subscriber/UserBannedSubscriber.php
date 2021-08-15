<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\UserBannedEvent;
use App\Exception\PremiumNotBanException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserBannedSubscriber implements EventSubscriberInterface
{
    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserBannedEvent::class => 'onUserBanned',
        ];
    }

    /**
     * @param UserBannedEvent $event
     * @throws PremiumNotBanException
     */
    public function onUserBanned(UserBannedEvent $event): void
    {
        if ($event->getUser()->isPremium()) {
            throw new PremiumNotBanException();
        }
    }
}

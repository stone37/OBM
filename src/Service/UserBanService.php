<?php

namespace App\Service;

use App\Event\UserBannedEvent;
use App\Entity\User;
use DateTime;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserBanService
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param User $user
     */
    public function ban(User $user): void
    {
        $user->setBannedAt(new DateTime());
        $this->dispatcher->dispatch(new UserBannedEvent($user));
    }
}

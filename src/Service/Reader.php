<?php

namespace App\Service;

use App\Entity\Thread;
use App\Entity\User;
use App\Manager\ThreadManager;
use Symfony\Component\Security\Core\Security;

class Reader
{
    private Security $security;

    private ThreadManager $threadManager;

    public function __construct(Security $security, ThreadManager $threadManager)
    {
        $this->security = $security;
        $this->threadManager = $threadManager;
    }

    public function markAsRead(Thread $thread)
    {
        $participant = $this->getAuthenticatedParticipant();

        if ($thread->isReadByParticipant($participant)) {
            return;
        }

        $this->threadManager->markAsReadByParticipant($thread, $participant);
    }

    public function markAsUnread(Thread $thread)
    {
        $participant = $this->getAuthenticatedParticipant();

        if (!$thread->isReadByParticipant($participant)) {
            return;
        }

        $this->threadManager->markAsUnreadByParticipant($thread, $participant);
    }

    /**
     * Gets the current authenticated user.
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface|User
     */
    private function getAuthenticatedParticipant()
    {
        return $this->security->getUser();
    }
}

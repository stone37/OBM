<?php

namespace App\Security;

use App\Entity\Thread;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class ThreadAuthorizer
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function canSeeThread(Thread $thread)
    {
        return $this->getAuthenticatedParticipant() && $thread->isParticipant($this->getAuthenticatedParticipant());
    }

    public function canDeleteThread(Thread $thread)
    {
        return $this->canSeeThread($thread);
    }

    public function canMessageParticipant(User $user)
    {
        return true;
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


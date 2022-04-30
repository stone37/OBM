<?php

namespace App\Service;

use App\Entity\Thread;
use App\Entity\User;
use App\Event\ThreadDeleteEvent;
use App\Event\ThreadUndeleteEvent;
use App\Security\ThreadAuthorizer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class ThreadDeleter
{
    private ThreadAuthorizer $authorizer;

    private Security $security;

    private EventDispatcherInterface $dispatcher;

    public function __construct(ThreadAuthorizer $authorizer, Security $security, EventDispatcherInterface $dispatcher)
    {
        $this->authorizer = $authorizer;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
    }

    public function markAsDeleted(Thread $thread)
    {
        if (!$this->authorizer->canDeleteThread($thread)) {
            throw new AccessDeniedException('You are not allowed to delete this thread');
        }
        $thread->setIsDeletedByParticipant($this->getAuthenticatedParticipant(), true);

        $this->dispatcher->dispatch(new ThreadDeleteEvent($thread));
    }

    public function markAsUndeleted(Thread $thread)
    {
        if (!$this->authorizer->canDeleteThread($thread)) {
            throw new AccessDeniedException('You are not allowed to delete this thread');
        }
        $thread->setIsDeletedByParticipant($this->getAuthenticatedParticipant(), false);

        $this->dispatcher->dispatch(new ThreadUnDeleteEvent($thread));
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


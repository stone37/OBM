<?php

namespace App\Provider;

use App\Entity\User;
use App\Manager\MessageManager;
use App\Manager\ThreadManager;
use App\Security\ThreadAuthorizer;
use App\Service\Reader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ThreadProvider
{
    /**
     * @var ThreadManager
     */
    private ThreadManager $threadManager;

    /**
     * @var MessageManager
     */
    private MessageManager $messageManager;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var Reader
     */
    protected $threadReader;

    /**
     * @var ThreadAuthorizer
     */
    private ThreadAuthorizer $authorizer;

    public function __construct(
        ThreadManager $threadManager,
        MessageManager $messageManager,
        Security $security, Reader $threadReader,
        ThreadAuthorizer $authorizer)
    {
        $this->threadManager = $threadManager;
        $this->messageManager = $messageManager;
        $this->security = $security;

        $this->threadReader = $threadReader;
        $this->authorizer = $authorizer;
    }

    public function getThreads()
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantThreads($participant);
    }

    public function getInboxThreads()
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantInboxThreads($participant);
    }

    public function getSentThreads()
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantSentThreads($participant);
    }

    public function getDeletedThreads()
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->threadManager->findParticipantDeletedThreads($participant);
    }

    public function getThread($threadId)
    {
        $thread = $this->threadManager->findThreadById($threadId);

        if (!$thread) {
            throw new NotFoundHttpException('There is no such thread');
        }

        if (!$this->authorizer->canSeeThread($thread)) {
            throw new AccessDeniedException('You are not allowed to see this thread');
        }

        // Load the thread messages before marking them as read
        // because we want to see the unread messages
        $thread->getMessages();
        $this->threadReader->markAsRead($thread);

        return $thread;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbUnreadMessages()
    {
        return $this->messageManager->getNbUnreadMessageByParticipant($this->getAuthenticatedParticipant());
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


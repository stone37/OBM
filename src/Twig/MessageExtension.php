<?php

namespace App\Twig;

use App\Entity\Thread;
use App\Entity\User;
use App\Provider\ThreadProvider;
use App\Security\ThreadAuthorizer;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MessageExtension extends AbstractExtension
{
    private Security $security;

    private ThreadProvider $provider;

    private ThreadAuthorizer $authorizer;

    private $nbUnreadMessagesCache;

    public function __construct(Security $security, ThreadProvider $provider, ThreadAuthorizer $authorizer)
    {
        $this->security = $security;
        $this->provider = $provider;
        $this->authorizer = $authorizer;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('app_message_is_read', array($this, 'isRead')),
            new TwigFunction('app_message_nb_unread', array($this, 'getNbUnread')),
            new TwigFunction('app_message_can_delete_thread', array($this, 'canDeleteThread')),
            new TwigFunction('app_message_deleted_by_participant', array($this, 'isThreadDeletedByParticipant')),
        );
    }

    /**
     * Tells if this readable (thread or message) is read by the current user.
     *
     * @return bool
     */
    public function isRead(Thread $thread)
    {
        return $thread->isReadByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * Checks if the participant can mark a thread as deleted.
     *
     * @param Thread $thread
     *
     * @return bool true if participant can mark a thread as deleted, false otherwise
     */
    public function canDeleteThread(Thread $thread)
    {
        return $this->authorizer->canDeleteThread($thread);
    }

    /**
     * Checks if the participant has marked the thread as deleted.
     *
     * @param Thread $thread
     *
     * @return bool true if participant has marked the thread as deleted, false otherwise
     */
    public function isThreadDeletedByParticipant(Thread $thread)
    {
        return $thread->isDeletedByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * Gets the number of unread messages for the current user.
     *
     * @return int
     */
    public function getNbUnread()
    {
        if (null === $this->nbUnreadMessagesCache) {
            $this->nbUnreadMessagesCache = $this->provider->getNbUnreadMessages();
        }

        return $this->nbUnreadMessagesCache;
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

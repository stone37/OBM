<?php

namespace App\Service;

use App\Entity\ThreadMessage;
use App\Event\ThreadMessageEvent;
use App\Manager\MessageManager;
use App\Manager\ThreadManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Sender
{
    /**
     * @var MessageManager
     */
    private MessageManager $messageManager;

    /**
     * @var ThreadManager
     */
    private ThreadManager $threadManager;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    public function __construct(MessageManager $messageManager, ThreadManager $threadManager, EventDispatcherInterface $dispatcher)
    {
        $this->messageManager = $messageManager;
        $this->threadManager = $threadManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function send(ThreadMessage $message)
    {
        $this->threadManager->saveThread($message->getThread(), false);
        $this->messageManager->saveMessage($message, false);

        /* Note: Thread::setIsDeleted() depends on metadata existing for all
         * thread and message participants, so both objects must be saved first.
         * We can avoid flushing the object manager, since we must save once
         * again after undeleting the thread.
         */
        $message->getThread()->setIsDeleted(false);
        $this->messageManager->saveMessage($message);

        $this->dispatcher->dispatch(new ThreadMessageEvent($message));
    }

    public function sendApi(ThreadMessage $message)
    {
        $this->threadManager->saveThread($message->getThread(), false);
        $this->messageManager->saveMessage($message, false);

        $message->getThread()->setIsDeleted(false);
        $this->messageManager->saveMessage($message, false);

        $this->dispatcher->dispatch(new ThreadMessageEvent($message));
    }
}


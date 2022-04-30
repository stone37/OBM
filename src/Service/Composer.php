<?php

namespace App\Service;

use App\Entity\Thread;
use App\Manager\MessageManager;
use App\Manager\ThreadManager;
use App\ThreadMessageBuilder\NewThreadMessageBuilder;
use App\ThreadMessageBuilder\ReplyMessageBuilder;

class Composer
{
    private MessageManager $messageManager;

    private ThreadManager $threadManager;

    public function __construct(MessageManager $messageManager, ThreadManager $threadManager)
    {
        $this->messageManager = $messageManager;
        $this->threadManager = $threadManager;
    }

    public function newThread()
    {
        $thread = $this->threadManager->createThread();
        $message = $this->messageManager->createMessage();

        return new NewThreadMessageBuilder($message, $thread);
    }

    public function reply(Thread $thread)
    {
        $message = $this->messageManager->createMessage();

        return new ReplyMessageBuilder($message, $thread);
    }
}

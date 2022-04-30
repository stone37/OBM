<?php

namespace App\ThreadMessageBuilder;

use App\Entity\ThreadMessage;
use App\Entity\Thread;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractMessageBuilder
{
    /**
     * The message we are building.
     *
     * @var ThreadMessage
     */
    protected $message;

    /**
     * The thread the message goes in.
     *
     * @var Thread
     */
    protected $thread;

    public function __construct(ThreadMessage $message, Thread $thread)
    {
        $this->message = $message;
        $this->thread = $thread;

        $thread->addMessage($message);
    }

    /**
     * Gets the created message.
     *
     * @return ThreadMessage the message created
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param  string
     *
     * @return AbstractMessageBuilder (fluent interface)
     */
    public function setBody(string $body)
    {
        $this->message->setBody($body);

        return $this;
    }

    /**
     * @param User|UserInterface $sender
     *
     * @return AbstractMessageBuilder (fluent interface)
     */
    public function setSender(User $sender)
    {
        $this->message->setSender($sender);
        $this->thread->addParticipant($sender);

        return $this;
    }
}

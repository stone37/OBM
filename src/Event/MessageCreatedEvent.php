<?php

namespace App\Event;

use App\Entity\Message;

class MessageCreatedEvent
{
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}

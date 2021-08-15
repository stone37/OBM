<?php

namespace App\Event;

use App\Entity\Message;

class PreMessageCreatedEvent
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

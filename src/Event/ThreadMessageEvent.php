<?php

namespace App\Event;

use App\Entity\ThreadMessage;

class ThreadMessageEvent
{
    private ThreadMessage $message;

    public function __construct(ThreadMessage $message)
    {
        $this->message = $message;
    }

    public function getMessage(): ThreadMessage
    {
        return $this->message;
    }
}

<?php

namespace App\Event;

use App\Entity\Thread;

class ThreadUndeleteEvent
{
    private Thread $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function getMessage(): Thread
    {
        return $this->thread;
    }
}


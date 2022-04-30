<?php

namespace App\Api\Controller;

use App\Provider\ThreadProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserThread extends AbstractController
{
    private ThreadProvider $provider;

    public function __construct(ThreadProvider $provider)
    {
        $this->provider = $provider;
    }

    public function __invoke($data)
    {
        return $this->provider->getThreads();
    }
}



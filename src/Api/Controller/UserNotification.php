<?php

namespace App\Api\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserNotification extends AbstractController
{
    private NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function __invoke($data)
    {
        $notifications = $this->service->forUser($this->getUser());
        $this->service->readAll($this->getUser());

        return $notifications;
    }
}



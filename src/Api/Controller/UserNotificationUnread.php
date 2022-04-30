<?php

namespace App\Api\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserNotificationUnread extends AbstractController
{
    private NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function __invoke($data)
    {
        return $this->json(['data' => $this->service->nbUnread($this->getUser())], Response::HTTP_OK);
    }
}



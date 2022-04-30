<?php

namespace App\Twig;

use App\Service\NotificationService;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NotificationExtension extends AbstractExtension
{
    private Security $security;
    private NotificationService $service;

    public function __construct(Security $security, NotificationService $service)
    {
        $this->security = $security;
        $this->service = $service;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('app_notification_nb_unread', array($this, 'getNbUnread')),
        );
    }

    /**
     * @return int
     */
    public function getNbUnread()
    {
        return $this->service->nbUnread($this->security->getUser());
    }
}

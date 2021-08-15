<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class MaintenanceListener
{
    private $twig;
    private $maintenance;

    public function __construct(Environment $twig, $maintenance)
    {
        $this->twig = $twig;
        $this->maintenance = $maintenance;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!count($this->maintenance['ipAuthorized'])) return;
        if (!file_exists($this->maintenance['path'])) return;

        if (!in_array($event->getRequest()->getClientIp(), $this->maintenance['ipAuthorized'])) {

            $event->setResponse(
                new Response($this->twig->render(
                    'site/maintenance/index.html.twig')/*, Response::HTTP_SERVICE_UNAVAILABLE*/
                )
            );
            $event->stopPropagation();
        }
    }

}

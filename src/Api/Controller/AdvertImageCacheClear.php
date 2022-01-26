<?php

namespace App\Api\Controller;

use App\Service\ApiOrphanageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvertImageCacheClear extends AbstractController
{
    private ApiOrphanageManager $orphanageManager;

    public function __construct(ApiOrphanageManager $orphanageManager)
    {
        $this->orphanageManager = $orphanageManager;
    }

    public function __invoke(SessionInterface $session)
    {
        if (!$session->isStarted()) {
            $session->start();
        }

        $session->set('app_advert_image', []);

        $this->orphanageManager->initClear();

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Cache image vide'], Response::HTTP_OK);
    }
}



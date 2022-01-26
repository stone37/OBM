<?php

namespace App\Api\Controller;

use App\Service\ApiOrphanageManager;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvertImageDelete extends AbstractController
{
    private ApiOrphanageManager $orphanageManager;

    public function __construct(ApiOrphanageManager $orphanageManager)
    {
        $this->orphanageManager = $orphanageManager;
    }

    public function __invoke(Request $request, SessionInterface $session)
    {
        if (!$session->isStarted()) {
            $session->start();
        }

        if (!$session->has('app_advert_image')) {
            return $this->json(['code' => Response::HTTP_NO_CONTENT, 'message' => 'Cette session n\'existe pas'], Response::HTTP_NO_CONTENT);
        }

        $data = $session->get('app_advert_image');
        $position = $request->attributes->get('position');

        $system = new Filesystem();
        $finder = new Finder();

        try {
            $finder->in($this->orphanageManager->getFindPath())->name(''.key($data[$position]).'');
        } catch (InvalidArgumentException $e) {
            $finder->append([]);
        }

        foreach ($finder as $file) {
            $system->remove((string) $file->getRealPath());
            array_splice($data, $position, 1);
            $session->set('app_advert_image', $data);
        }

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'File delete successfully'], Response::HTTP_OK);
    }
}



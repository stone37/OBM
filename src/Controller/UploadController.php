<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Controller\Traits\UploadTrait;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class UploadController
 * @package App\Controller
 */
class UploadController extends AbstractController
{
    use ControllerTrait;
    use UploadTrait;

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function add(Request $request, SessionInterface $session): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Mauvais requête');

        $files = $this->getFiles($request->files);

        foreach ($files as $file) {
            try {
                try {
                    $this->upload($file, $session);
                } catch (FileException $e) {}
            } catch (UploadException $e) {}
        }

        return new JsonResponse([]);
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @param $pos
     * @return JsonResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function delete(Request $request, SessionInterface $session, $pos)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Mauvais requête');
        if (!$session->has('app_advert_image')) return $this->createNotFoundException('Page introuvable');

        $data = $session->get('app_advert_image');

        $system = new Filesystem();
        $finder = new Finder();

        try {
            $finder->in($this->getFindPath($session))->name(''.key($data[$pos]).'');
        } catch (InvalidArgumentException $e) {
            $finder->append([]);
        }

        foreach ($finder as $file) {
            $system->remove((string) $file->getRealPath());
            array_splice($data, $pos, 1);
            $session->set('app_advert_image', $data);
        }

        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @return JsonResponse|\Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function principale(Request $request, SessionInterface $session)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Mauvais requête');
        if (!$session->has('app_advert_image')) return $this->createNotFoundException('Page introuvable');

        $pos = $request->query->get('pos');
        $data = $session->get('app_advert_image');

        $array = [];

        foreach ($data as $cle => $values) {
            if ($cle == $pos) {
                foreach ($values as $key => $value) {
                    $array[] = [$key => 1];
                }
            } else {
                foreach ($values as $key => $value) {
                    $array[] = [$key => 0];
                }
            }
        }

        $session->set('app_advert_image', $array);

        return new JsonResponse([]);
    }
}

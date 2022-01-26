<?php

namespace App\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvertImagePrincipale extends AbstractController
{
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

        $array = [];

        foreach ($data as $cle => $values) {
            if ($cle == $position) {
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

        return $this->json(['code' => Response::HTTP_OK, 'data' => $session->get('app_advert_image')], Response::HTTP_OK);
    }
}



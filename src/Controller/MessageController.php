<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Service\Composer;
use App\Service\Sender;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageController extends AbstractController
{
    /**
     * @param Advert $advert
     * @param Request $request
     * @param Composer $composer
     * @param Sender $sender
     * @param ValidatorInterface $validator
     * @param ReCaptcha $reCaptcha
     * @return JsonResponse
     */
    public function newThread(
        Advert $advert,
        Request $request,
        Composer $composer,
        Sender $sender,
        ValidatorInterface $validator
        //ReCaptcha $reCaptcha
        )
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $message = $composer->newThread()
            ->setSender($this->getUser())
            ->addRecipient($advert->getUser())
            ->setAdvert($advert)
            ->setBody($request->request->get('content'))
            ->getMessage();

        $errors = $validator->validate($message);

        if (!$this->isCsrfTokenValid('advert-message', $request->request->get('_token'))) {
            $errors[] = 'Le jeton CSRF est invalide.';
        }

        /*if (!$reCaptcha->verify($request->request->get('recaptchaToken'))->isSuccess()) {
            $errors[] = 'Erreur pendant l\'envoi de votre message';
        }*/

        if (!count($errors)) {
           $sender->send($message);

            return new JsonResponse([
                'success' => true,
                'message' => 'Votre message a été envoyer à l\'annonçeur',
            ]);
        }

        $data = [];

        foreach ($errors as $error) {
            $data[] = $error->getMessage();
        }

        return new JsonResponse(['success' => false, 'errors' => json_encode($data)]);
    }
}

<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Service\Composer;
use App\Service\Sender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserThreadCreate extends AbstractController
{
    private Composer $composer;
    private Sender $sender;

    public function __construct(Composer $composer, Sender $sender)
    {
        $this->composer = $composer;
        $this->sender = $sender;
    }

    public function __invoke(Request $request, Advert $advert, $data)
    {
        $content = json_decode($request->getContent(), true);

        $message = $this->composer->newThread()
            ->setSender($this->getUser())
            ->addRecipient($advert->getUser())
            ->setAdvert($advert)
            ->setBody($content['message'])
            ->getMessage();

        $this->sender->send($message);

      return $this->json(['message' => 'Votre message a été envoyer'], Response::HTTP_CREATED);
    }
}



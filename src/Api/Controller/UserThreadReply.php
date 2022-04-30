<?php

namespace App\Api\Controller;

use App\Entity\Thread;
use App\Service\Composer;
use App\Service\Sender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserThreadReply extends AbstractController
{
    private Composer $composer;
    private Sender $sender;

    public function __construct(Composer $composer, Sender $sender)
    {
        $this->composer = $composer;
        $this->sender = $sender;
    }

    /**
     * @param Request $request
     * @param Thread $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request, $data)
    {
        $content = json_decode($request->getContent(), true);

        $message = $this->composer->reply($data)
            ->setSender($this->getUser())
            ->setBody($content['message'])
            ->getMessage();

        $this->sender->send($message);

      return $this->json(['message' => 'Votre message a été envoyer'], Response::HTTP_CREATED);
    }
}



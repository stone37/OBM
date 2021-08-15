<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Message;
use App\Event\MessageCreatedEvent;
use App\Event\PreMessageCreatedEvent;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AdvertMessageController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param ValidatorInterface $validator
     * @param Advert $advert
     * @return JsonResponse
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        ValidatorInterface $validator,
        Advert $advert
    )
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $message = (new Message())
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime())
            ->setAdvert($advert)
            ->setNotification((bool)($request->request->get('notification') ?? false))
            ->setAdSimilar((bool)($request->request->get('similar') ?? false))
            ->setContent($request->request->get('content') ?? null);

        if ($this->getUser()) {
            $message->setFirstname($this->getUser()->getFirstName())
                ->setPhone($this->getUser()->getPhone())
                ->setEmail($this->getUser()->getEmail());
        } else {
            $message->setFirstname($request->request->get('firstname'))
                ->setPhone($request->request->get('phone'))
                ->setEmail($request->request->get('email'))
                ->setDeleted(true);
        }

        $errors = $validator->validate($message);

        if (!$this->isCsrfTokenValid('advert-message', $request->request->get('_token'))) {
            $errors[] = 'Le jeton CSRF est invalide.';
        }

        if (!count($errors)) {
            $dispatcher->dispatch(new PreMessageCreatedEvent($message));

            $em->persist($message);
            $em->flush();

            $dispatcher->dispatch(new MessageCreatedEvent($message));

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


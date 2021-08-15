<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Favorite;
use App\Entity\User;
use App\Event\FavoriteEvent;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FavoriteController extends AbstractController
{
    use ControllerTrait;

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $advert = $em->getRepository(Advert::class)->find($id);
        $user = $this->getUserOrThrow();

        if (!$advert) {
            return $this->json(['data' => ['type' => 'error', 'message' => 'Cette annonce n\'existe pas']]);
        }

        $favorite = $em->getRepository(Favorite::class)->findOneBy(['advert' => $advert, 'user' => $user]);

        if ($favorite) {
            return $this->json(['data' => ['type' => 'error', 'message' => 'Cette annonce est déjà dans vos favoris']]);
        }

        $favorite = (new Favorite())
            ->setAdvert($advert)
            ->setUser($user);

        $em->persist($favorite);
        $em->flush();

        $dispatcher->dispatch(new FavoriteEvent($favorite));

        return $this->json(['data' => ['type' => 'success', 'message' => 'L\'annonce a été ajouter à vos favoris']]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $advert = $em->getRepository(Advert::class)->find($id);
        $user = $this->getUserOrThrow();

        $favorite = $em->getRepository(Favorite::class)->findOneBy([
            'advert' => $advert,
            'user' => $user
        ]);

        if (!$favorite) return $this->json(['data' => ['type' => 'error', 'message' => 'Une erreur est survenu, le favoris n\'existe pas']]);

        $em->remove($favorite);
        $em->flush();

        $dispatcher->dispatch(new FavoriteEvent($favorite));

        return $this->json(['data' => ['type' => 'success', 'message' => 'L\'annonce a été retirer de vos favoris']]);
    }

    /**
     * @return User
     */
    private function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}


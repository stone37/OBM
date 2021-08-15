<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\User;
use App\Manager\SettingsManager;
use App\Service\NotificationService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NotificationController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param NotificationService $service
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(NotificationService $service)
    {
        $notifications = $service->forUser($this->getUserOrThrow());

        return $this->render('site/notifications/index.html.twig', [
            'notifications' => $notifications,
            'settings' => $this->settings,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function readAll(NotificationService $service): JsonResponse
    {
        $service->readAll($this->getUserOrThrow());

        return new JsonResponse();
    }

    private function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    /**
     * @Route(name="ping", path="/ping", methods={"POST"})
     */
    public function ping(HubInterface $hub)
    {
        $update = new Update('http://monsite/ping', "[]");
        $hub->publish($update);

        return $this->redirectToRoute("app_home");
    }
}


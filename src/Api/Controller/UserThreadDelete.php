<?php

namespace App\Api\Controller;

use App\Entity\Thread;
use App\Manager\ThreadManager;
use App\Service\ThreadDeleter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserThreadDelete extends AbstractController
{
    private ThreadDeleter $deleter;
    private ThreadManager  $manager;

    public function __construct(ThreadDeleter $deleter, ThreadManager  $manager)
    {
        $this->deleter = $deleter;
        $this->manager = $manager;
    }

    /**
     * @param Thread $data
     */
    public function __invoke($data)
    {
        $this->deleter->markAsDeleted($data);
        $this->manager->saveThread($data);

        return $this->json(['message' => 'Votre conversation a été supprimer'], Response::HTTP_NO_CONTENT);
    }
}



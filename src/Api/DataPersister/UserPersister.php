<?php

namespace App\Api\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $request;
    private $manager;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        UserManager $manager
    ) {
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $manager;
    }

    /**
     * @param object $data
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     *
     * @throws \Exception
     */
    public function persist($data, array $context = [])
    {
        $data = $this->manager->generateApiUser($data, $this->request);

        $this->em->persist($data);
        $this->em->flush();
    }

    /**
     * @param User $data
     */
    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}




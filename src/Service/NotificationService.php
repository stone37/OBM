<?php

namespace App\Service;

use App\Entity\Advert;
use App\Entity\User;
use App\Entity\Notification;
use App\Event\NotificationCreatedEvent;
use App\Event\NotificationReadEvent;
use App\Repository\NotificationRepository;
use App\Security\ChannelVoter;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationService
{
    private $em;
    private $dispatcher;
    private $security;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        Security $security
    ) {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->security = $security;
    }

    /**
     * Envoie une notification sur un canal particulier.
     */
    public function notifyChannel(string $channel, string $message, ?object $entity = null, string $url = null): Notification
    {
        /**
        //$url = $entity ? $this->serializer->serialize($entity, PathEncoder::FORMAT) : null;*/
        $notification = (new Notification())
            ->setMessage($message)
            ->setUrl($url)
            ->setTarget($entity ? $this->getHashForEntity($entity) : null)
            ->setCreatedAt(new DateTime())
            ->setChannel($channel);

        $this->em->persist($notification);
        $this->em->flush();
        $this->dispatcher->dispatch(new NotificationCreatedEvent($notification));

        return $notification;
    }

    /**
     * Envoie une notification à un utilisateur.
     *
     * @param User $user
     * @param string $message
     * @return Notification
     * @throws \Doctrine\ORM\ORMException
     */
    public function notifyUser(User $user, string $message, Advert $entity, string $url = null): Notification
    {
       /* /** @var NotificationRepository $repository
        $repository = $this->em->getRepository(Notification::class);*/

        $notification = (new Notification())
            ->setMessage($message)
            ->setUrl($url)
            ->setTarget($this->getHashForEntity($entity))
            ->setCreatedAt(new DateTime())
            ->setUser($user)
            ->setAdvert($entity);

        $this->em->persist($notification);
        $this->em->flush();
        $this->dispatcher->dispatch(new NotificationCreatedEvent($notification));

        return $notification;
    }

    /**
     * @return Notification[]
     * @param User|UserInterface $user
     */
    public function forUser(User $user): array
    {
        /** @var NotificationRepository $repository */
        $repository = $this->em->getRepository(Notification::class);

        return $repository->findRecentForUser($user, $this->getChannelsForUser($user));
    }

    /**
     * @param User|UserInterface $user
     */
    public function readAll(User $user): void
    {
        $user->setNotificationsReadAt(new DateTimeImmutable());
        $this->em->flush();
        $this->dispatcher->dispatch(new NotificationReadEvent($user));
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface|User  $user
     * @return int
     */
    public function nbUnread(User $user)
    {
        $count = 0;
        $notifications = $this->em->getRepository(Notification::class)
            ->findRecentForUser($user, $this->getChannelsForUser($user));

        foreach($notifications as $notification) {
            if ($notification->isRead()) $count++;
        }

        return $count;
    }

    /**
     * Renvoie les salons auquel l'utilisateur peut s'abonner.
     *
     * @return string[]
     */
    public function getChannelsForUser(User $user): array
    {
        $channels = [
            'user/'.$user->getId(),
            'public',
        ];

        if ($this->security->isGranted(ChannelVoter::LISTEN_ADMIN)) {
            $channels[] = 'admin';
        }

        return $channels;
    }

    /**
     * Extrait un hash pour une notification className::id.
     */
    private function getHashForEntity(object $entity): string
    {
        $hash = get_class($entity);
        if (method_exists($entity, 'getId')) {
            $hash .= '::'.(string) $entity->getId();
        }

        return $hash;
    }
}

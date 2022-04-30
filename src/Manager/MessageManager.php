<?php

namespace App\Manager;

use App\Entity\Thread;
use App\Entity\ThreadMessage;
use App\Entity\ThreadMessageMetadata;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PDO;

class MessageManager
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createMessage()
    {
       return new ThreadMessage();
    }

    public function getNbUnreadMessageByParticipant(User $user)
    {
        return $this->em->getRepository(ThreadMessage::class)->getNbUnreadMessageByParticipant($user);
    }

    /**
     * Marks all messages of this thread as read by this participant.
     *
     * @param Thread $thread
     * @param User $user
     * @param bool $isRead
     */
    public function markIsReadByThreadAndParticipant(Thread $thread, User $user, $isRead)
    {
        dump($thread, $user);
        foreach ($thread->getMessages() as $message) {
            $this->markIsReadByParticipant($message, $user, $isRead);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveMessage(ThreadMessage $message, $andFlush = true)
    {
        $this->denormalize($message);
        $this->em->persist($message);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * Marks the message as read or unread by this participant.
     *
     * @param ThreadMessage $message
     * @param User $user
     * @param bool $isRead
     */
    protected function markIsReadByParticipant(ThreadMessage $message, User $user, $isRead)
    {
        $meta = $message->getMetadataForParticipant($user);

        if (!$meta || $meta->getIsRead() == $isRead) {
            return;
        }

        $this->em->createQueryBuilder()
            ->update(ThreadMessageMetadata::class, 'm')
            ->set('m.isRead', '?1')
            ->setParameter('1', (bool) $isRead, PDO::PARAM_BOOL)
            ->where('m.id = :id')
            ->setParameter('id', $meta->getId())
            ->getQuery()
            ->execute();
    }

    /*
     * DENORMALIZATION
     *
     * All following methods are relative to denormalization
     */

    /**
     * Performs denormalization tricks.
     */
    protected function denormalize(ThreadMessage $message)
    {
        $this->doMetadata($message);
    }

    /**
     * Ensures that the message metadata are up to date.
     */
    protected function doMetadata(ThreadMessage $message)
    {
        foreach ($message->getThread()->getMetadata() as $threadMeta) {
            $meta = $message->getMetadataForParticipant($threadMeta->getParticipant());
            if (!$meta) {
                $meta = $this->createMessageMetadata();
                $meta->setParticipant($threadMeta->getParticipant());

                $message->addMetadata($meta);
            }
        }
    }

    protected function createMessageMetadata()
    {
        return new ThreadMessageMetadata();
    }
}

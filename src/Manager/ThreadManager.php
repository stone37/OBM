<?php

namespace App\Manager;

use App\Entity\Advert;
use App\Entity\Thread;
use App\Entity\ThreadMetadata;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ThreadManager
{
    private EntityManagerInterface $em;

    private MessageManager $messageManager;

    public function __construct(EntityManagerInterface $em, MessageManager $messageManager)
    {
        $this->em = $em;
        $this->messageManager = $messageManager;
    }

    /**
     * @return Thread
     */
    public function createThread()
    {
        return new Thread();
    }

    public function findThreadById($id)
    {
        return $this->em->getRepository(Thread::class)->find($id);
    }

    public function findParticipantThreads(User $user)
    {
        return $this->em->getRepository(Thread::class)->getParticipantThreads($user);
    }

    public function findParticipantInboxThreads(User $user)
    {
        return $this->em->getRepository(Thread::class)->getParticipantInboxThreads($user);
    }

    public function findParticipantSentThreads(User $user)
    {
        return $this->em->getRepository(Thread::class)->getParticipantSentThreads($user);
    }

    public function findParticipantDeletedThreads(User $user)
    {
        return $this->em->getRepository(Thread::class)->getParticipantDeletedThreads($user);
    }

    public function findThreadsCreatedBy(User $user)
    {
        return $this->em->getRepository(Thread::class)->getThreadsCreatedBy($user);
    }

    /**
     * @param Advert $advert
     * @param User|\Symfony\Component\Security\Core\User\UserInterface $user
     * @return mixed
     */
    public function nbThreadsCreatedByAdvert(Advert $advert, User $user)
    {
        return $this->em->getRepository(Thread::class)->getThreadsCreatedByAdvert($advert, $user);
    }

    /**
     * @param Advert $advert
     * @param User|\Symfony\Component\Security\Core\User\UserInterface $user
     * @return mixed
     */
    public function findThreadsCreatedByAdvert(Advert $advert, User $user)
    {
        return $this->em->getRepository(Thread::class)->findThreadsCreatedByAdvert($advert, $user);
    }

    public function markAsReadByParticipant(Thread $thread, User $user)
    {
        $this->messageManager->markIsReadByThreadAndParticipant($thread, $user, true);
    }

    public function markAsUnreadByParticipant(Thread $thread, User $user)
    {
        $this->messageManager->markIsReadByThreadAndParticipant($thread, $user, false);
    }

    public function saveThread(Thread $thread, $andFlush = true)
    {
        $this->denormalize($thread);
        $this->em->persist($thread);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    public function deleteThread(Thread $thread)
    {
        $this->em->remove($thread);
        $this->em->flush();
    }

    /*
     * DENORMALIZATION
     *
     * All following methods are relative to denormalization
     */

    /**
     * Performs denormalization tricks.
     */
    protected function denormalize(Thread $thread)
    {
        $this->doMetadata($thread);
        $this->doCreatedByAndAt($thread);
        $this->doDatesOfLastMessageWrittenByOtherParticipant($thread);
    }

    /**
     * Ensures that the thread metadata are up to date.
     */
    protected function doMetadata(Thread $thread)
    {
        // Participants
        foreach ($thread->getParticipants() as $participant) {
            $meta = $thread->getMetadataForParticipant($participant);
            if (!$meta) {
                $meta = $this->createThreadMetadata();
                $meta->setParticipant($participant);

                $thread->addMetadata($meta);
            }
        }

        // Messages
        foreach ($thread->getMessages() as $message) {
            $meta = $thread->getMetadataForParticipant($message->getSender());
            if (!$meta) {
                $meta = $this->createThreadMetadata();
                $meta->setParticipant($message->getSender());
                $thread->addMetadata($meta);
            }

            $meta->setLastParticipantMessageDate($message->getCreatedAt());
        }
    }

    /**
     * Ensures that the createdBy & createdAt properties are set.
     */
    protected function doCreatedByAndAt(Thread $thread)
    {
        if (!($message = $thread->getFirstMessage())) {
            return;
        }

        if (!$thread->getCreatedAt()) {
            $thread->setCreatedAt($message->getCreatedAt());
        }

        if (!$thread->getCreatedBy()) {
            $thread->setCreatedBy($message->getSender());
        }
    }

    /**
     * Update the dates of last message written by other participants.
     */
    protected function doDatesOfLastMessageWrittenByOtherParticipant(Thread $thread)
    {
        foreach ($thread->getMetadata() as $meta) {
            $participantId = $meta->getParticipant()->getId();
            $timestamp = 0;

            foreach ($thread->getMessages() as $message) {
                if ($participantId != $message->getSender()->getId()) {
                    $timestamp = max($timestamp, $message->getTimestamp());
                }
            }

            if ($timestamp) {
                $date = new DateTime();
                $date->setTimestamp($timestamp);
                $meta->setLastMessageDate($date);
            }
        }
    }

    protected function createThreadMetadata()
    {
        return new ThreadMetadata();
    }
}

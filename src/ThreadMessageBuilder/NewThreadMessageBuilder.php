<?php

namespace App\ThreadMessageBuilder;

use App\Entity\Advert;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class NewThreadMessageBuilder extends AbstractMessageBuilder
{
    /**
     * The thread subject.
     *
     * @param Advert
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function setAdvert(Advert $advert)
    {
        $this->thread->setAdvert($advert);

        return $this;
    }

    /**
     * @param User $recipient
     *
     * @return NewThreadMessageBuilder (fluent interface)
     */
    public function addRecipient(User $recipient)
    {
        $this->thread->addParticipant($recipient);

        return $this;
    }

    /**
     * @param Collection $recipients
     *
     * @return NewThreadMessageBuilder
     */
    public function addRecipients(Collection $recipients)
    {
        foreach ($recipients as $recipient) {
            $this->addRecipient($recipient);
        }

        return $this;
    }
}

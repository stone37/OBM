<?php

namespace App\Subscriber;

use App\Entity\Advert;
use App\Entity\AdvertPicture;
use App\Entity\Product;
use App\Event\AdvertValidateEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdvertImageSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            AdvertValidateEvent::class => 'onImage',
        ];
    }

    public function onImage(AdvertValidateEvent $event): void
    {
        /** @var Advert $advert */
        $advert = $event->getAdvert();

        if ($advert->isOptionPhoto()) {
            return;
        }

        /** @var Product $option */
        $option = $this->em->getRepository(Product::class)->getOptionPhoto();
        $count = 0;

        /** @var AdvertPicture $image */
        foreach ($advert->getImages() as $image) {
            $count++;

            if ($count > $option->getPhotoFree()) {
                $this->em->remove($image);
            }
        }

        $this->em->flush();
    }
}

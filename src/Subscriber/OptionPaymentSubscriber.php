<?php

namespace App\Subscriber;

use App\Entity\Advert;
use App\Entity\Command;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Vignette;
use App\Event\PaymentEvent;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OptionPaymentSubscriber implements EventSubscriberInterface
{
    const TYPE_OPTION_VISUAL = 'ov';
    const TYPE_OPTION_PHOTO = 'op';
    const TYPE_VIGNETTE = 'v';

    private $em;
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @return array<string,string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PaymentEvent::class => 'onPayment',
        ];
    }

    /**
     * @param PaymentEvent $event
     */
    public function onPayment(PaymentEvent $event)
    {
        /** @var Command $order */
        $order = $event->getOrder();

        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {

            /** @var Product $product */
            $product = $item->getProduct();

            if ($product->getCategory() === self::TYPE_OPTION_PHOTO ||
                $product->getCategory() === self::TYPE_OPTION_VISUAL) {

                if (!$this->session->get('app_advert')) {
                    return;
                }

                /** @var Advert $advert */
                $advert = $this->em->getRepository(Advert::class)->find($this->session->get('app_advert'));

                if ($product->getCategory() === self::TYPE_OPTION_VISUAL) {
                    $this->applyOptionVisual($product, $advert);
                } else {
                    $advert->setOptionPhoto(true);

                    $this->em->flush();
                }
            } elseif ($product->getCategory() === self::TYPE_VIGNETTE) {
                if (!$this->session->get('app_vignette')) {
                    return;
                }

                /** @var Vignette $vignette */
                $vignette = $this->em->getRepository(Vignette::class)->find($this->session->get('app_vignette'));

                $end = new DateTimeImmutable();
                $vignette->setEndDate($end->add(new DateInterval("P{$product->getDays()}D")));
                $vignette->setEnabled(true);

                $this->em->flush();
            }
        }
    }

    /**
     * @param Product $product
     * @param Advert $advert
     */
    private function applyOptionVisual(Product $product, Advert $advert)
    {
        $now = new DateTimeImmutable();

        if ($product->getType() == 1) {
            $optionEnd = $advert->getOptionAdHeadEnd() ?: new DateTimeImmutable();
            $optionEnd = $optionEnd > $now ? $optionEnd : new DateTimeImmutable();

            $advert->setOptionAdHeadEnd($optionEnd->add(new DateInterval("P{$product->getDays()}D")));

        } elseif ($product->getType() == 2) {
            $optionEnd = $advert->getOptionAdUrgentsEnd() ?: new DateTimeImmutable();

            $advert->setOptionAdUrgentsEnd($optionEnd->add(new DateInterval("P{$product->getDays()}D")));

        } elseif ($product->getType() == 3) {
            $optionEnd = $advert->getOptionAdGalleryEnd() ?: new DateTimeImmutable();
            $optionEnd = $optionEnd > $now ? $optionEnd : new DateTimeImmutable();

            $advert->setOptionAdGalleryEnd($optionEnd->add(new DateInterval("P{$product->getDays()}D")));

        } elseif ($product->getType() == 4) {
            $optionEnd = $advert->getOptionAdVedetteEnd() ?: new DateTimeImmutable();
            $optionEnd = $optionEnd > $now ? $optionEnd : new DateTimeImmutable();

            $advert->setOptionAdVedetteEnd($optionEnd->add(new DateInterval("P{$product->getDays()}D")));
        } else {
            $optionEnd = $advert->getOptionAdEncadreEnd() ?: new DateTimeImmutable();
            $optionEnd = $optionEnd > $now ? $optionEnd : new DateTimeImmutable();

            $advert->setOptionAdEncadreEnd($optionEnd->add(new DateInterval("P{$product->getDays()}D")));
        }

        $this->em->flush();
    }
}

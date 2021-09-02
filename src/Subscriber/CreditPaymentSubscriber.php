<?php

namespace App\Subscriber;

use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Wallet;
use App\Event\PaymentEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreditPaymentSubscriber implements EventSubscriberInterface
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
            PaymentEvent::class => 'onPayment',
        ];
    }

    /**
     * @param PaymentEvent $event
     */
    public function onPayment(PaymentEvent $event)
    {
        $user = $event->getOrder()->getUser();

        if ($event->getPayment()->getMethod() === Payment::METHOD_WALLET) {
            /** @var Wallet $wallet */
            $wallet = ($user->getWallet())->addPurchase($event->getPayment());
            $user->setWallet($wallet);
        } else {
            /** @var Product $product */
            $product = $event->getOrder()->getItems()->first()->getProduct();

            if ($product->getCategory() === 'credit') {
                $amount = $product->getPrice() + $product->getCredit();

                /** @var Wallet $wallet */
                $wallet = ($user->getWallet())
                    ->addDeposit($event->getPayment())
                    ->setBalance($user->getWallet()->getBalance()+$amount)
                    ->setDeposit($user->getWallet()->getDeposit()+$amount);

                $user->setWallet($wallet);
            }
        }

        $this->em->flush();
    }
}

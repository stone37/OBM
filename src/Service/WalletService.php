<?php

namespace App\Service;

use App\Entity\User;
use App\Manager\OrderManager;

class WalletService
{
    private $manager;

    public function __construct(OrderManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        $order = $this->manager->getCurrent();
        $user = $order->getUser();
        $summary = $this->manager->summary();

        if ($user->getWallet()->getBalance() > $summary->amountPaid()) {
            $user = $this->payment($user, $summary->amountPaid());
            $order->setUser($user);

            return [
                'status' => 'COMPLETED',
                'data' => $order,
            ];
        } else {
            return [
                'status' => 'ERROR',
                'data' => $order,
            ];
        }
    }

    /**
     * @param User $user
     * @param int $amount
     * @return User
     */
    private function payment(User $user, int $amount): User
    {
        $wallet = ($user->getWallet())
            ->setSpent($user->getWallet()->getSpent()+$amount)
            ->setBalance($user->getWallet()->getBalance()-$amount);

        return $user->setWallet($wallet);
    }
}

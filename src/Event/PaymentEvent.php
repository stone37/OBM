<?php

namespace App\Event;

use App\Entity\Command;
use App\Entity\Payment;

class PaymentEvent
{
    private $payment;
    private $order;

    public function __construct(Payment $payment, Command $order)
    {
        $this->payment = $payment;
        $this->order = $order;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @return Command
     */
    public function getOrder(): Command
    {
        return $this->order;
    }
}


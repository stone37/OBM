<?php

namespace App\Service;

use App\Entity\Command;

class Summary
{
    const DISCOUNT_PF = 'fixed';
    const DISCOUNT_PD = 'percent';

    /**
     * @var Command
     */
    private $order;

    /**
     * Summary constructor.
     *
     * @param Command $order
     */
    public function __construct(Command $order)
    {
        $this->order = $order;
    }

    public function getItems()
    {
        return $this->order->getItems();
    }

    /**
     * @return int
     */
    public function getItemsPriceTotal(): int
    {
        return $this->order->getPriceTotalTva();
    }

    /**
     * @return int
     */
    public function getPriceItemsBeforeDiscount(): int
    {
        return $this->order->getPriceTotal();
    }

    /**
     * @return int
     */
    public function getTvaPriceTotal(): int
    {
        return $this->order->getTotalTva();
    }

    public function amountPaid()
    {
        return ($this->order->getPriceTotalTva() - $this->getDiscount());
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        $price = 0;
        $discount = $this->order->getDiscount();

        if ($discount) {
            if ($discount->getType() === self::DISCOUNT_PF) {
                $price = ($this->getPriceItemsBeforeDiscount() - $discount->getDiscount());
            } elseif ($discount->getType() === self::DISCOUNT_PD) {
                $price = (($this->getPriceItemsBeforeDiscount() * $discount->getDiscount()) / 100);
            }
        }

        return $price;
    }

    /**
     * @return bool
     */
    public function hasDiscount(): bool
    {
        return (bool) $this->order->getDiscount();
    }

    /**
     * @return Command
     */
    public function getOrder(): Command
    {
        return $this->order;
    }
}
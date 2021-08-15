<?php

namespace App\Manager;

use App\Entity\Command;
use App\Entity\Discount;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Event\OrderEvent;
use App\Service\Summary;
use App\Storage\OrderSessionStorage;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Security;

class OrderManager
{
    /**
     * @var OrderSessionStorage
     */
    private $storage;

    /**
     * @var Command
     */
    private $order;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        OrderSessionStorage $storage,
        EntityManagerInterface $em,
        EventDispatcherInterface $eventDispatcher,
        Security $security)
    {
        $this->storage = $storage;
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->security = $security;
        $this->order = $this->getCurrent();
    }

    /**
     * @return Command
     */
    public function getCurrent(): Command
    {
        $order = $this->storage->getOrderById();

        if ($order !== null) {
            return $order;
        }

        /** @var User $user */
        $user = $this->security->getUser();

        $order = new Command();
        $order->setUser($user);

        return $order;
    }

    /**
     * @param Product $product
     * @param $priceTTC
     */
    public function addItem(Product $product, $priceTTC): void
    {
        if (!$this->containsProduct($product)) {

            $item = new OrderItem();
            $item->setProduct($product);
            $item->setOrder($this->order);
            $item->setQuantity(1);
            $item->setPriceTotal($priceTTC);
            $item->setOrder($this->order);

            $this->em->persist($item);

            $this->eventDispatcher->dispatch(new OrderEvent($this->order));
        }
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function containsProduct(Product $product): bool
    {
        foreach ($this->order->getItems() as $item) {
            if ($item->getProduct() === $product) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Product $product
     */
    public function removeItem(Product $product): void
    {
        if ($this->order && $this->order->getItems()) {
            foreach ($this->order->getItems() as $item) {
                if ($item->getProduct() === $product) {
                    $this->order->removeItem($item);
                }
            }

            $this->eventDispatcher->dispatch(new OrderEvent($this->order));

            $this->em->persist($this->order);
            $this->em->flush();
        }
    }

    /**
     * @return Collection|null
     */
    public function items(): ?Collection
    {
        return $this->order->getItems();
    }

    /**
     * Vide les product d'une commande
     */
    public function clearItems()
    {
        foreach ($this->order->getItems() as $item) {
            $this->em->remove($item);
        }

        $this->em->flush();
    }

    /**
     * @return bool
     */
    public function hasAdvert()
    {
        return (bool) $this->order->getAdvert();
    }

    /**
     * @param Discount $discount
     */
    public function setDiscount(Discount $discount): void
    {
        if ($this->order) {

            $this->order->setDiscount($discount);

            $this->eventDispatcher->dispatch(new OrderEvent($this->order));

            $this->em->persist($this->order);
            $this->em->flush();
        }
    }

    /**
     * Supprime une commande
     */
    public function clear(): void
    {
        $this->em->remove($this->order);
        $this->em->flush();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->order->getItems();
    }

    /**
     * @return Summary
     */
    public function summary(): Summary
    {
        return new Summary($this->order);
    }
}


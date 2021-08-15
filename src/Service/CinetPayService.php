<?php

namespace App\Service;

use App\Entity\Command;
use App\Exception\PaymentFailedException;
use App\Manager\OrderManager;
use CinetPay\CinetPay;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CinetPayService
{
    const TYPE_CREDIT = 'credit';
    const TYPE_OV = 'ov';
    const TYPE_OP = 'op';

    private $request;
    private $manager;
    private $generateUrl;

    public function __construct(RequestStack $requestStack, OrderManager $manager, UrlGeneratorInterface $urlGenerator)
    {
        $this->request = $requestStack;
        $this->manager = $manager;
        $this->generateUrl = $urlGenerator;
    }

    public function createPayment()
    {
        $summary = $this->manager->summary();
        $order = $this->manager->getCurrent();

        $btnType = 2;//1-5xwxxw
        $btnSize = 'large';
        $formName = "goCinetPay"; // nom du formulaire CinetPay

        $cp = new CinetPay(getenv('CINETPAY_ID'), getenv('CINETPAY_KEY'));

        try {
            $cp->setTransId(CinetPay::generateTransId())
                ->setDesignation($this->description($order))
                ->setTransDate(new DateTime("Y-m-d H:i:s"))
                ->setAmount($summary->amountPaid())
                ->setCurrency('XOF')
                ->setDebug(true)// Valorisé à true, si vous voulez activer le mode debug sur cinetpay afin d'afficher toutes les variables envoyées chez CinetPay
                ->setCustom($order->getUser()->getId())// optional
                //->setNotifyUrl($this->generateUrl->generate('app_order_notify', [], UrlGeneratorInterface::ABSOLUTE_URL))// optional
                ->setReturnUrl($this->generateUrl->generate('app_order_payment', [], UrlGeneratorInterface::ABSOLUTE_URL))// optional
                ->setCancelUrl($this->generateUrl->generate('app_cart_index', [], UrlGeneratorInterface::ABSOLUTE_URL))// optional
                ->displayPayButton($formName, $btnType, $btnSize);
        } catch (Exception $e) {
            throw PaymentFailedException::fromHttpException($e);
        }
    }

    private function description(Command $order)
    {
        $product = $order->getItems()->first()->getProduct();

        if ($product->getCategory() === self::TYPE_CREDIT) {
            return 'Achat de credit';
        } elseif ($product->getCategory() === self::TYPE_OV || $product->getCategory() === self::TYPE_OP) {
            return 'Achat d\'option';
        } else {
            return 'Achat de pack pro';
        }
    }
}

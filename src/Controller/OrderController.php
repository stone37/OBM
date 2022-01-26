<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Command;
use App\Entity\Payment;
use App\Entity\Product;
use App\Event\PaymentEvent;
use App\Manager\OrderManager;
use App\Manager\SettingsManager;
use App\Service\CinetPayService;
use App\Service\Summary;
use App\Service\UniqueSuiteNumberGenerator;
use App\Service\WalletService;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrderController extends AbstractController
{
    use ControllerTrait;

    private $em;
    private $session;
    private $settings;
    private $storage;
    private $manager;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session,
        SettingsManager $settings,
        OrderSessionStorage $storage,
        OrderManager $manager)
    {
        $this->em = $em;
        $this->session = $session;
        $this->settings = $settings->get();
        $this->storage = $storage;
        $this->manager = $manager;
    }

    public function invoice($id)
    {
        $order = $this->em->getRepository(Command::class)->find($id);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('site/order/invoice.html.twig', [
            'settings' => $this->settings,
            'order' => $order
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mfacture.pdf", ["Attachment" => false]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param OrderManager $manager
     * @param SessionInterface $session
     * @return Response
     */
    public function prepareOrder()
    {
        $this->manager->clearItems();

        $totalHT = 0;
        $totalTVA = 0;

        $products = $this->em->getRepository(Product::class)
                        ->findArray(array_values($this->session->get('app_cart')));

        foreach ($products as $product) {
            $priceTTC = ((($product->getPrice() * $product->getTva()->getValue())/100)+$product->getPrice());

            $this->manager->addItem($product, $priceTTC);

            $totalHT += $product->getPrice();
            $totalTVA += $priceTTC - $product->getPrice();
        }

        $order = $this->manager->getCurrent();

        $order->setUser($this->getUser());
        $order->setValidated(false);
        $order->setReference(false);

        $order->setPriceTotal($totalHT);
        $order->setTotalTva($totalTVA);
        $order->setPriceTotalTva($totalHT+$totalTVA);

        if (!$this->storage->has()) {
            $this->em->persist($order);
        }

        $this->em->flush();

        $this->storage->set($order->getId());

        return new Response($order->getId());
    }

    /**
     * @param EntityManagerInterface $em
     * @param WalletService $service
     * @param EventDispatcherInterface $dispatcher
     * @param UniqueSuiteNumberGenerator $generator
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateCreditOrder(
        WalletService $service,
        EventDispatcherInterface $dispatcher,
        UniqueSuiteNumberGenerator $generator)
    {
        $response = $service->execute();
        
        if ($response['status'] === 'COMPLETED') {
            /** @var Command $order */
            $order = $response['data'];

            if (!$order || $order->isValidated())
                throw $this->createNotFoundException('La commande n\'existe pas');

            $order->setValidated(true);
            $order->setReference($generator->generate(9));
            $summary = new Summary($order);

            $payment = (new Payment())
                ->setMethod(Payment::METHOD_WALLET)
                ->setOrder($order)
                ->setPrice($summary->amountPaid())
                ->setDiscount($summary->getDiscount())
                ->setTax($order->getTotalTva())
                ->setEnabled(true);

            $this->em->persist($payment);
            $this->em->flush();

            $dispatcher->dispatch(new PaymentEvent($payment, $order));

            $this->session->remove('orderId');
            $this->session->remove('app_cart');
            $this->session->remove('app_advert');
            $this->session->remove('app_vignette');

            $this->addFlash('success', 'Felicitation, votre paiement a été effectué avec succès');

            return $this->redirectToRoute('app_dashboard_invoice_index');
        } else {
            $this->addFlash('error', 'Désolé, votre paiement a échoué cause: credit est insuffisant');

            return $this->redirectToRoute('app_cart_validate');
        }
    }

    public function validateOrder(CinetPayService $service)
    {
        return $this->redirectToRoute('app_order_payment');

        /**try {
            $service->createPayment();
        } catch (PaymentFailedException $e) {
            $this->addFlash('error', 'Erreur lors du paiement');
        }*/
    }

    /**
     * @param EntityManagerInterface $em
     * @param OrderManager $manager
     * @param EventDispatcherInterface $dispatcher
     * @param UniqueSuiteNumberGenerator $generator
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function payment(
        OrderManager $manager,
        EventDispatcherInterface $dispatcher,
        UniqueSuiteNumberGenerator $generator)
    {
        $order = $manager->getCurrent();

        if (!$order || $order->isValidated())
            throw $this->createNotFoundException('La commande n\'existe pas');

        $order->setValidated(true);
        $order->setReference($generator->generate(9));
        $summary = new Summary($order);

        $payment = (new Payment())
            ->setMethod(Payment::METHOD_CARD)
            ->setOrder($order)
            ->setPrice($summary->amountPaid())
            ->setDiscount($summary->getDiscount())
            ->setTax($order->getTotalTva())
            ->setEnabled(true);

        $this->em->persist($payment);
        $this->em->flush();

        $dispatcher->dispatch(new PaymentEvent($payment, $order));

        $this->session->remove('orderId');
        $this->session->remove('app_cart');
        $this->session->remove('app_advert');
        $this->session->remove('app_vignette');

        $this->addFlash('success', 'Felicitation, votre paiement a été effectué avec succès');

        return $this->redirectToRoute('app_dashboard_invoice_index');


        /*if ($request->request->has('cpm_trans_id') && !empty($request->request->get('cpm_trans_id'))) {
            try {
                $cp = new CinetPay(getenv('CINETPAY_ID'), getenv('CINETPAY_KEY'));

                // Reprise exacte des bonnes données chez CinetPay
                $cp->setTransId($request->request->get('cpm_trans_id'))->getPayStatus();

                if ($cp->isValidPayment()) {
                    $order = $manager->getCurrent();

                    if (!$order || $order->isValidated())
                        throw $this->createNotFoundException('La commande n\'existe pas');

                    $order->setValidated(true);
                    $order->setReference($generator->generate(9));

                    $payment = (new Payment())
                        ->setMethod(Payment::METHOD_CARD)
                        ->setOrder($order)
                        ->setEnabled(true);

                    $em->persist($payment);
                    $em->flush();

                    $dispatcher->dispatch(new PaymentEvent($payment, $order));

                    $session->remove('orderId');
                    $session->remove('app_cart');
                    $session->remove('app_advert');

                    $this->addFlash('success', 'Felicitation, votre paiement a été effectué avec succès');

                    return $this->redirectToRoute('app_dashboard_order_index_active');
                } else {

                    $this->addFlash('error', 'Echec, votre paiement a échoué pour cause : ' . $cp->_cpm_error_message);

                    return $this->redirectToRoute('app_cart_validate');
                }
            } catch (Exception $e) {
                $this->addFlash('error', "Erreur :" . $e->getMessage());

                return $this->redirectToRoute('app_cart_validate');
            }
        }

        return $this->redirectToRoute('app_home');*/
    }


    /**
    $paymentData = [
    "cpm_site_id" => $cp->_cpm_site_id,
    "signature" => $cp->_signature,
    "cpm_amount" => $cp->_cpm_amount,
    "cpm_trans_id" => $cp->_cpm_trans_id,
    "cpm_custom" => $cp->_cpm_custom,
    "cpm_currency" => $cp->_cpm_currency,
    "cpm_payid" => $cp->_cpm_payid,
    "cpm_payment_date" => $cp->_cpm_payment_date,
    "cpm_payment_time" => $cp->_cpm_payment_time,
    "cpm_error_message" => $cp->_cpm_error_message,
    "payment_method" => $cp->_payment_method,
    "cpm_phone_prefixe" => $cp->_cpm_phone_prefixe,
    "cel_phone_num" => $cp->_cel_phone_num,
    "cpm_ipn_ack" => $cp->_cpm_ipn_ack,
    "created_at" => $cp->_created_at,
    "updated_at" => $cp->_updated_at,
    "cpm_result" => $cp->_cpm_result,
    "cpm_trans_status" => $cp->_cpm_trans_status,
    "cpm_designation" => $cp->_cpm_designation,
    "buyer_name" => $cp->_buyer_name,
    ];
     */
}


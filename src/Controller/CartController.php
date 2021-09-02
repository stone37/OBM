<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Command;
use App\Entity\Discount;
use App\Entity\Product;
use App\Form\DiscountType;
use App\Form\PaymentType;
use App\Manager\OrderManager;
use App\Manager\SettingsManager;
use App\Service\Summary;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CartController extends AbstractController
{
    use ControllerTrait;

    private $manager;
    private $settings;

    public function __construct(OrderManager $manager, SettingsManager $settings)
    {
        $this->manager = $manager;
        $this->settings = $settings->get();
    }

    /**
     * Ajoute un produit au panier
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @return JsonResponse
     */
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');
        if (!$session->has('app_cart')) return new JsonResponse(false);

        $products = [];

        foreach ($session->get('app_cart') as $id) {
            $products[] = $em->getRepository(Product::class)->find($id);
        }

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = $serializer->serialize($products, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['orderItems']]);

        return new JsonResponse($response);
    }

    /**
     * Retire un produit du panier
     *
     * @param Request $request
     * @param SessionInterface $session
     * @param $id
     * @return JsonResponse
     */
    public function add(Request $request, SessionInterface $session, $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');
        if (!$session->has('app_cart')) {$session->set('app_cart', []);}

        $cart = $session->get('app_cart');

        if (in_array($id, $cart)) {return new JsonResponse(['success' => false]);}

        $cart[] = $id;
        $session->set('app_cart', $cart);

        return new JsonResponse(['success' => true]);
    }

    /**
     * Replace un produit du panier
     *
     * @param Request $request
     * @param SessionInterface $session
     * @param $id
     * @param $newId
     * @return JsonResponse
     */
    public function replace(Request $request, SessionInterface $session, $id, $newId)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $cart = $session->get('app_cart');

        if (in_array($id, $cart)) {
            unset($cart[array_search($id, $cart)]);
            $session->set('app_cart', $cart);
        }

        $cart[] = $newId;
        $session->set('app_cart', $cart);

        return new JsonResponse(['success' => true]);
    }

    /**
     * Retire un produit du panier
     *
     * @param Request $request
     * @param SessionInterface $session
     * @param $id
     * @return JsonResponse
     */
    public function delete(Request $request, SessionInterface $session, $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $cart = $session->get('app_cart');

        if (!in_array($id, $cart)) {return new JsonResponse(['success' => false]);}

        unset($cart[array_search($id, $cart)]);
        $session->set('app_cart', $cart);

        return new JsonResponse(['success' => true]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param OrderManager $manager
     * @return RedirectResponse
     */
    public function discount(Request $request, OrderManager $manager): RedirectResponse
    {
        $form = $this->createForm(DiscountType::class, $manager->getCurrent());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $discount = $this->getDoctrine()->getRepository(Discount::class)->findOneBy([
                'code' => $form->get('discountCode')->getData()
            ]);

            if ($discount !== null) {
                $manager->setDiscount($discount);
                $this->addFlash('success', 'Le code promo a été utilisé');
            } else {
                $this->addFlash('error', 'Ce code promo n\'existe pas');
            }
        }

        return $this->redirectToRoute('app_cart_validate');
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validate(Request $request, SessionInterface $session, EntityManagerInterface $em)
    {
        if ($request->query->has('id')) {
            $session->set('app_advert', $request->query->get('id'));
        }

        $prepareOrder = $this->forward('App\Controller\OrderController::prepareOrder');
        $order = $em->getRepository(Command::class)->find($prepareOrder->getContent());
        $setDiscountForm = $this->createForm(DiscountType::class, $order);
        $setPaymentForm = $this->createForm(PaymentType::class, $order);

        $setPaymentForm->handleRequest($request);

        if ($setPaymentForm->isSubmitted() && $setPaymentForm->isValid()){
            $em->flush();

            if ($order->getPaymentMethod() === Command::CARD_PAYMENT) {
                return $this->redirectToRoute('app_order_validate');
            } else {
                return $this->redirectToRoute('app_order_credit_validate');
            }
        }

        return $this->render('site/cart/validate.html.twig', [
            'order' => new Summary($order),
            'setDiscountForm' => $setDiscountForm->createView(),
            'setPaymentForm' => $setPaymentForm->createView(),
            'settings' => $this->settings,
        ]);
    }
}


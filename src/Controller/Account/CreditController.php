<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Product;
use App\Entity\Settings;
use App\Service\SettingsManager;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreditController extends AbstractController
{
    use ControllerTrait;

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        OrderSessionStorage $storage,
        SessionInterface $session)
    {
        $settings = $this->settings;

        if (!$settings->isActiveCredit()) throw $this->createNotFoundException('Page introuvable');

        $storage->remove();

        $products = $em->getRepository(Product::class)->findBy(['type' => 7], ['price' => 'desc']);

        $form = $this->createFormBuilder()
                    ->add('credit', HiddenType::class)
                    ->setMethod('POST')->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('app_cart', [$form->getData()['credit']]);

            return $this->redirectToRoute('app_cart_validate');
        }

        return $this->render('user/credit/index.html.twig', [
            'settings' => $settings,
            'form' => $form->createView(),
            'products' => $products
        ]);
    }
}

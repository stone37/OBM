<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Product;
use App\Entity\Vignette;
use App\Form\VignetteType;
use App\Manager\VignetteManager;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class VignetteController extends AbstractController
{
    use ControllerTrait;

    private $settings;
    private $breadcrumbs;

    public function __construct(SettingsManager $settings, Breadcrumbs $breadcrumbs)
    {
        $this->settings = $settings->get();
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param VignetteManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $em, VignetteManager $manager)
    {
        $vignettes = $manager->getList($request);
        $product = $em->getRepository(Product::class)->getMinPrice(8);

        return $this->render('site/vignette/index.html.twig', [
            'vignettes' => $vignettes,
            'product' => $product,
            'settings' => $this->settings,
        ]);
    }

    /**
     * @param Request $request
     * @param VignetteManager $manager
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, VignetteManager $manager, EntityManagerInterface $em, SessionInterface $session)
    {
        $this->breadcrumbs
            ->addItem('Accueil', $this->generateUrl('app_home'))
            ->addItem('Créer une vignette');

        $products = $em->getRepository(Product::class)->findBy(
            ['category' => 'v', 'type' => 8, 'enabled' => true], ['price' => 'asc']);
        $product = $em->getRepository(Product::class)->getMinPrice(8);

        $vignette = $manager->createVignette();

        $form = $this->createForm(VignetteType::class, $vignette, ['em' => $em]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($vignette);
            $em->flush();

            $session->set('app_cart', [$form->getData()['productId']]);
            $session->set('app_vignette', $vignette->getId());

            return $this->redirectToRoute('app_cart_validate');
        }

        return $this->render('site/vignette/create.html.twig', [
            'products' => $products,
            'product' => $product,
            'form' => $form->createView(),
            'settings' => $this->settings,
        ]);
    }

    /**
     * @param Vignette $vignette
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function show(Vignette $vignette)
    {
        return $this->redirect($vignette->getSiteWeb());
    }
}

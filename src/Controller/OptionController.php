<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Product;
use App\Manager\AdvertManager;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class OptionController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EntityManagerInterface $em)
    {
        $gallery = $em->getRepository(Product::class)->getOptionVisualByType(3);

        $urgents = $em->getRepository(Product::class)->getOptionVisualByType(2);

        $headers = $em->getRepository(Product::class)->getOptionVisualByType(1);

        $vedettes = $em->getRepository(Product::class)->getOptionVisualByType(4);

        $encadres = $em->getRepository(Product::class)->getOptionVisualByType(5);

        return $this->render('site/advert/option/visual.html.twig', [
            'gallery' => $gallery,
            'urgents' => $urgents,
            'headers' => $headers,
            'vedettes' => $vedettes,
            'encadres' => $encadres,
            'settings' => $this->settings,
        ]);
    }

    /**
     * @param AdvertManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gallery(AdvertManager $manager)
    {
        $galleries = $manager->getAdvertGallery();
        shuffle($galleries);

        return $this->render('site/advert/option/_gallery.html.twig', [
            'settings' => $this->settings,
            'galleries' => $galleries
        ]);
    }

    /**
     * @param Request $request
     * @param AdvertManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vedette(Request $request, AdvertManager $manager)
    {
        $vedettes = $manager->getAdvertVedette(
            $request->attributes->get('category_slug'),
            $request->attributes->get('sub_category_slug'),
            $request->attributes->get('sub_division_slug')
        );

        shuffle($vedettes);

        return $this->render('site/advert/option/_vedette.html.twig', [
            'settings' => $this->settings,
            'vedettes' => $vedettes,
        ]);
    }

    /**
     * @param AdvertManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function header(Request $request, AdvertManager $manager)
    {
        $headers = $manager->getAdvertHeader(
            $request->attributes->get('category_slug'),
            $request->attributes->get('sub_category_slug'),
            $request->attributes->get('sub_division_slug')
        );

        shuffle($headers);

        return $this->render('site/advert/option/_head.html.twig', [
            'settings' => $this->settings,
            'headers' => $headers,
        ]);
    }
}

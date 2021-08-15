<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Controller\Traits\UploadTrait;
use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Event\AdvertBadEvent;
use App\Event\AdvertCreatedInitializeEvent;
use App\Event\AdvertCreateEvent;
use App\Event\AdvertListEvent;
use App\Event\AdvertPreCreatedEvent;
use App\Event\AdvertShowEvent;
use App\Manager\AdvertManager;
use App\Model\Search;
use App\Service\CategoryService;
use App\Service\SettingsManager;
use App\Storage\OrderSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class AdvertController extends AbstractController
{
    use ControllerTrait;
    use UploadTrait;

    private $settings;
    private $breadcrumbs;
    private $session;

    public function __construct(
        SettingsManager $settings,
        SessionInterface $session,
        Breadcrumbs $breadcrumbs)
    {
        $this->settings = $settings->get();
        $this->breadcrumbs = $breadcrumbs;
        $this->session = $session;
    }

    /**
     * Choisir une catégorie pour une nouvelle annonce
     *
     * @IsGranted("ROLE_USER")
     *
     * @param EntityManagerInterface $em
     * @param Breadcrumbs $breadcrumbs
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chooseCategory(EntityManagerInterface $em)
    {
        $this->breadcrumbs
            ->addItem('Accueil', $this->generateUrl('app_home'))
            ->addItem('Sélectionner une catégorie');

        return $this->render('site/advert/category.html.twig', [
            'settings' => $this->settings,
            'categories' => $this->getCategories($em)
        ]);
    }

    /**
     * Cree une nouvelle annonce
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param AdvertManager $advertManager
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        AdvertManager $manager,
        OrderSessionStorage $storage
    )
    {
        $storage->remove();

        $user = $this->getUserOrThrow();
        $advert = $manager->createAdvert($user);
        $optionPhoto = $em->getRepository(Product::class)->getOptionPhoto();

        $dispatcher->dispatch(new AdvertCreatedInitializeEvent($advert, $request));

        $form = $this->createForm($manager->createFormType(), $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new AdvertPreCreatedEvent($advert, $request);

            $dispatcher->dispatch($event);

            $manager->updateAdvert($advert);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('app_home');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(new AdvertCreateEvent($advert, $request));

            return $response;
        } else {
            $dispatcher->dispatch(new AdvertBadEvent($advert, $request));
        }

        return $this->render('site/advert/create/create.html.twig', [
            'settings' => $this->settings,
            'categories' => $this->getCategories($em),
            'form' => $form->createView(),
            'view' => $manager->createFormViewRoute(),
            'advert' => $advert,
            'optionPhoto' => $optionPhoto,
        ]);
    }

    /**
     * Affiche une annonce
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param AdvertManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        AdvertManager $manager)
    {
        $advert = $manager->getAdvert($request->attributes->get('reference'));
        $advertiserAd = $em->getRepository(Advert::class)->userAdvertActiveNumber($advert->getUser());

        $this->showBreadcrumbs($advert);
        
        $dispatcher->dispatch(new AdvertShowEvent($advert, $request));

        if ($advert->getUser() == $this->getUser()) {
            $gallery = $em->getRepository(Product::class)->getOptionVisualByType(3);
            $urgents = $em->getRepository(Product::class)->getOptionVisualByType(2);
            $headers = $em->getRepository(Product::class)->getOptionVisualByType(1);
            $vedettes = $em->getRepository(Product::class)->getOptionVisualByType(4);
            $encadres = $em->getRepository(Product::class)->getOptionVisualByType(5);
            $optionPhoto = $em->getRepository(Product::class)->getOptionPhoto();

            return $this->render('site/advert/show/show.html.twig', [
                'settings' => $this->settings,
                'advert' => $advert,
                'advertiserAdNumber' => $advertiserAd,
                'view' => $manager->createShowView(),
                'gallery' => $gallery,
                'urgents' => $urgents,
                'headers' => $headers,
                'vedettes' => $vedettes,
                'encadres' => $encadres,
                'photo' => $optionPhoto,
            ]);
        }

        return $this->render('site/advert/show/show.html.twig', [
            'settings' => $this->settings,
            'advert' => $advert,
            'advertiserAdNumber' => $advertiserAd,
            'view' => $manager->createShowView(),
        ]);
    }

    /**
     * Liste les annonce par catégorie
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @param AdvertManager $manager
     * @param EventDispatcherInterface $dispatcher
     * @param CategoryService $categoryService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $em,
        AdvertManager $manager,
        EventDispatcherInterface $dispatcher,
        CategoryService $categoryService)
    {
        $category = $categoryService->getCategoryPrincipale();

        $this->breadcrumbs($category);

        $dispatcher->dispatch(new AdvertListEvent($request, $category));

        $search = new Search();
        $search->setCategory($request->attributes->get('category_slug'));
        $search->setSubCategory($request->attributes->get('sub_category_slug'));
        $search->setSubDivision($request->attributes->get('sub_division_slug'));
        $search = $this->hydrate($request, $search);

        $form = $this->createForm($manager->createSearchFormType(), $search);
        $form->handleRequest($request);

        $qb = $manager->getAdvertLists($search);
        $adverts = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('site/advert/index/index.html.twig', [
            'adverts' => $adverts,
            'settings' => $this->settings,
            'form' => $form->createView(),
            'form_mobile' => $form->createView(),
            'view' => $manager->createSearchFormViewRoute(),
            'category' => $category,
            'categories' => $this->getCategories($em),
        ]);
    }

    /**
     * Recherche les annonces par ajax
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function search(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');
        $query = $request->request->get('q');
        $adverts = $em->getRepository(Advert::class)->search($query);

        $response = $this->responseFormatter($adverts, $query);

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = $serializer->serialize($response, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => []]);

        return new JsonResponse($response);
    }

    /**
     * Fil d'area d'affichage d'une annonce
     *
     * @param Advert $advert
     * @return Breadcrumbs
     */
    private function showBreadcrumbs(Advert $advert)
    {
        $this->breadcrumbs->addItem('Accueil', $this->generateUrl('app_home'));
        $this->breadcrumbs->addItem(ucfirst($advert->getCategory()), $this->generateUrl('app_advert_index', [
            'category_slug' => $advert->getCategory()->getSlug()]));
        $this->breadcrumbs->addItem(ucfirst($advert->getSubCategory()), $this->generateUrl('app_advert_index_s', [
                'category_slug' => $advert->getCategory()->getSlug(),
                'sub_category_slug' => $advert->getSubCategory()->getSlug()]));

        if ($advert->getSubDivision()) {
            $this->breadcrumbs->addItem(ucfirst($advert->getSubDivision()), $this->generateUrl('app_advert_index_ss', [
                'category_slug' => $advert->getCategory()->getSlug(),
                'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                'sub_division_slug' => $advert->getSubDivision()->getSlug()]));

            $this->breadcrumbs->addItem(ucfirst($advert->getLocation()->getName()), $this->generateUrl('app_advert_index_ss', [
                'category_slug' => $advert->getCategory()->getSlug(),
                'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                'sub_division_slug' => $advert->getSubDivision()->getSlug(),
                'city' => $advert->getLocation()->getName()]));
        } else {
            $this->breadcrumbs->addItem(ucfirst($advert->getLocation()->getName()), $this->generateUrl('app_advert_index_s', [
                'category_slug' => $advert->getCategory()->getSlug(),
                'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                'city' => $advert->getLocation()->getName()]));
        }

        $this->breadcrumbs->addItem('Code '.$advert->getReference());

        return $this->breadcrumbs;
    }

    /**
     * @param Category $category
     * @return Breadcrumbs
     */
    private function breadcrumbs(Category $category)
    {
        $this->breadcrumbs->addItem('Accueil', $this->generateUrl('app_home'));

        $categories = [];

        do {
            $categories[] = $category;
            $category = $category->getParent() ? $category->getParent() : null;
        } while($category);

        for ($i = (count($categories)-1); $i >= 0; $i--) {
            if ((count($categories)-1) == $i) {
                $this->breadcrumbs->addItem(ucfirst($categories[$i]->getName()),
                    $this->generateUrl('app_advert_index', ['category_slug' => $categories[$i]->getSlug()]));
            } else {
                $this->breadcrumbs->addItem(ucfirst($categories[$i]->getName()),
                    $this->generateUrl('app_advert_index_s', [
                        'category_slug' => $categories[count($categories)-1]->getSlug(),
                        'sub_category_slug' => $categories[$i]->getSlug()
                    ]));
            }
        }

        return $this->breadcrumbs;
    }

    /**
     * @param Request $request
     * @param Search $search
     * @return Search
     */
    private function hydrate(Request $request, Search $search)
    {
        if ($request->query->has('marque'))
            $search->setMarque($request->query->get('marque'));
        if ($request->query->has('order'))
            $search->setOrder($request->query->get('order'));

        return $search;
    }

    /**
     * @return User
     */
    private function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!($user instanceof User)) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    /**
     * @param array $adverts
     * @param $query
     * @return array
     */
    private function responseFormatter(array $adverts, $query)
    {
        $response = [];

        /** @var Advert $advert */
        foreach ($adverts as $advert) {
            $subcategory = $advert->getSubCategory()->getName();
            $marque = $advert->getMarque();
            $title = $advert->getTitle();

            $hasBrand = preg_match("#{$query}#i", "'.$marque.'");
            $hasSubcategory = preg_match("#{$query}#i", "'.$subcategory.'");
            $hasTitle = preg_match("#{$query}#i", "'.$title.'");

            if (!$advert->getSubDivision()) {
                $route = $this->generateUrl('app_advert_index_s', [
                    'category_slug' => $advert->getCategory()->getSlug(),
                    'sub_category_slug' => $advert->getSubCategory()->getSlug()
                ]);

                if ($hasBrand) {
                    $route = $this->generateUrl('app_advert_index_s', [
                        'category_slug' => $advert->getCategory()->getSlug(),
                        'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                        'marque' => $advert->getMarque()
                    ]);

                    $response[] = [
                        'title' => $advert->getMarque().' dans <span>'
                            .strtolower($advert->getSubCategory()->getName()).'</span>',
                        'route' => $route
                    ];
                } elseif ($hasSubcategory) {
                    $response[] = [
                        'title' => $advert->getSubCategory()->getName(),
                        'route' => $route
                    ];
                } elseif ($hasTitle) {
                    $response[] = [
                        'title' => $this->ellipse($advert->getTitle(), 8, '')
                            .' dans <span>'.strtolower($advert->getSubCategory()->getName()).'</span>',
                        'route' => $route
                    ];
                } else {
                    $response[] = [
                        'title' => $this->ellipse($advert->getDescription(), 8, '')
                            .' dans <span>'.strtolower($advert->getSubCategory()->getName()).'</span>',
                        'route' => $route
                    ];
                }
            } else {
                $subdivision = $advert->getSubDivision()->getName();
                $hasSubDivision = preg_match("#{$query}#i", "'.$subdivision.'");

                $route = $this->generateUrl('app_advert_index_ss', [
                    'category_slug' => $advert->getCategory()->getSlug(),
                    'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                    'sub_division_slug' => $advert->getSubDivision()->getSlug()
                ]);

                if ($hasBrand) {
                    $route = $this->generateUrl('app_advert_index_ss', [
                        'category_slug' => $advert->getCategory()->getSlug(),
                        'sub_category_slug' => $advert->getSubCategory()->getSlug(),
                        'sub_division_slug' => $advert->getSubDivision()->getSlug(),
                        'marque' => $advert->getMarque()
                    ]);

                    $response[] = [
                        'title' => $advert->getMarque().' dans <span>'
                            .strtolower($advert->getSubDivision()->getName()).'</span>',
                        'route' => $route
                    ];
                } elseif ($hasSubDivision) {
                    $response[] = [
                        'title' => $advert->getSubDivision()->getName(),
                        'route' => $route
                    ];
                } elseif ($hasTitle) {
                    $response[] = [
                        'title' => $this->ellipse($advert->getTitle(), 8, '')
                            .' dans <span>'.strtolower($advert->getSubCategory()->getName()).'</span>',
                        'route' => $route
                    ];
                } else {
                    $response[] = [
                        'title' => $this->ellipse($advert->getDescription(), 8, '')
                            .' dans <span class="category">'.strtolower($advert->getSubCategory()->getName()).'</span>',
                        'route' => $route
                    ];
                }
            }
        }

        return $response;
    }

    /**
     * @param $str
     * @param $n_chars
     * @param string $crop_str
     * @return string
     */
    private function ellipse($str, $n_chars, $crop_str='...')
    {
        $buff = strip_tags($str);
        if(strlen($buff) > $n_chars)
        {
            $cut_index=strpos($buff,' ', $n_chars);
            $buff=substr($buff,0,($cut_index===false? $n_chars: $cut_index+1)).$crop_str;
        }

        return $buff;
    }
}

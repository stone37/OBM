<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Category;
use App\Form\SearchGlobalMobileType;
use App\Form\SearchGlobalType;
use App\Manager\AdvertManager;
use App\Model\Search;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class SearchController extends AbstractController
{
    use ControllerTrait;

    private $paginator;
    private $settings;

    public function __construct(PaginatorInterface $paginator, SettingsManager $settings)
    {
        $this->paginator = $paginator;
        $this->settings = $settings->get();
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $em, SessionInterface $session)
    {
        $categories = $em->getRepository(Category::class)->getWithParentNull();

        $search = new Search();

        $form = $this->createForm(SearchGlobalType::class, $search, [
            'action' => $this->generateUrl('app_search_index')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($session->has('app_user_search_city') &&
                !empty($session->get('app_user_search_city'))) {
                $search->setCity($session->get('app_user_search_city'));
            }

            if ($session->has('app_user_search_zone') &&
                !empty($session->get('app_user_search_zone'))) {
                $search->setZone($session->get('app_user_search_zone'));
            }

            if ($search->getCategory()) {
                $category = $em->getRepository(Category::class)->find((int)$search->getCategory());

                return $this->redirectToRoute('app_advert_index', [
                    'category_slug' => $category->getSlug(),
                    'data' => $search->getData(),
                    'city' => $search->getCity(),
                    'zone' => $search->getZone(),
                ]);
            } else {
                return $this->redirectToRoute('app_search_result', [
                    'data' => $search->getData(),
                    'city' => $search->getCity(),
                    'zone' => $search->getZone(),
                ]);
            }
        }

        return $this->render('site/search/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexM(Request $request, EntityManagerInterface $em)
    {
        $search = new Search();

        $form = $this->createForm(SearchGlobalMobileType::class, $search, [
            'action' => $this->generateUrl('app_search_index_m')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($search->getCategory()) {
                $category = $em->getRepository(Category::class)->find((int)$search->getCategory());

                return $this->redirectToRoute('app_advert_index', [
                    'category_slug' => $category->getSlug(),
                    'data' => $search->getData(),
                    'city' => $search->getCity(),
                    'zone' => $search->getZone(),
                ]);
            } else {
                return $this->redirectToRoute('app_search_result', [
                    'data' => $search->getData(),
                    'city' => $search->getCity(),
                    'zone' => $search->getZone(),
                ]);
            }
        }

        return $this->render('site/search/index_m.html.twig', [
            'form_mobile' => $form->createView()
        ]);
    }

    public function result(
        Request $request,
        EntityManagerInterface $em,
        Breadcrumbs $breadcrumbs,
        AdvertManager $manager,
        PaginatorInterface $paginator)
    {
        $breadcrumbs->addItem('Accueil', $this->generateUrl('app_home'))
                    ->addItem('Liste d\'annonce');

        $search = (new Search())
            ->setData($request->query->get('data'))
            ->setCity($request->query->get('city'))
            ->setZone($request->query->get('zone'));

        $qb = $manager->getAdvertLists($search);
        $adverts = $paginator->paginate($qb, $request->query->getInt('page', 1), 15);

        return $this->render('site/search/result.html.twig', [
            'adverts' => $adverts,
            'settings' => $this->settings,
            'categories' => $this->getCategories($em),
        ]);
    }
}

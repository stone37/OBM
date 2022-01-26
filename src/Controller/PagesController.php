<?php

namespace App\Controller;

use App\Entity\Help;
use App\Entity\HelpCategory;
use App\Form\Filter\SearchType;
use App\Manager\SettingsManager;
use App\Model\Search;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PagesController extends AbstractController
{
    private $settings;

    public function __construct(SettingsManager $manager)
    {
        $this->settings = $manager->get();
    }

    public function env(): Response
    {
        return $this->render('site/pages/env.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function confidentialite(): Response
    {
        return $this->render('site/pages/confidentialite.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function help(Request $request, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(HelpCategory::class)
            ->findBySlug($request->attributes->get('category_slug'));

        $categories = $em->getRepository(HelpCategory::class)->getEnabledParent($category);

        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($search->getTitle()) {
                return $this->redirectToRoute('app_help_result_search', [
                    'data' => $search->getTitle(),
                ]);
            }
        }

        return $this->render('site/pages/help.html.twig', [
            'categories' => $categories,
            'category' => $category,
            'form' => $form->createView(),
            'settings' => $this->settings,
        ]);
    }

    public function helpShow(Request $request, EntityManagerInterface $em, $slug)
    {
        $category = $em->getRepository(HelpCategory::class)
            ->findBySlug($request->attributes->get('category_slug'));

        $help = $em->getRepository(Help::class)->getBySlug($slug);

        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($search->getTitle()) {
                return $this->redirectToRoute('app_help_result_search', [
                    'data' => $search->getTitle(),
                ]);
            }
        }

        return $this->render('site/pages/helpShow.html.twig', [
            'help' => $help,
            'category' => $category,
            'form' => $form->createView(),
            'settings' => $this->settings,
        ]);
    }

    public function helpResult(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator, $data)
    {
        $search = (new Search())->setTitle($data);

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $helps = $em->getRepository(Help::class)->search($search);
        $helps = $paginator->paginate($helps, $request->query->getInt('page', 1), 15);

        return $this->render('site/pages/helpResult.html.twig', [
            'helps' => $helps,
            'form' => $form->createView(),
            'settings' => $this->settings,
        ]);
    }

    public function mention(): Response
    {
        return $this->render('site/pages/mention.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function cgu(): Response
    {
        return $this->render('site/pages/cgu.html.twig', [
            'settings' => $this->settings,
        ]);
    }

    public function charte(): Response
    {
        return $this->render('site/pages/charte.html.twig', [
            'settings' => $this->settings,
        ]);
    }
}

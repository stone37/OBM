<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Entity\User;
use App\Form\Filter\AdminAdvertType;
use App\Model\Admin\AdvertSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class UserAdvertController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        User $user)
    {
        $search = new AdvertSearch();

        $form = $this->createForm(AdminAdvertType::class, $search, ['em' => $em]);

        $form->handleRequest($request);

        $qb = $em->getRepository(Advert::class)->getUserAdmin($search, $user);

        $adverts = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/user/advert/index.html.twig', [
            'adverts' => $adverts,
            'user' => $user,
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(EntityManagerInterface $em, $id)
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        return $this->render('admin/user/advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }
}



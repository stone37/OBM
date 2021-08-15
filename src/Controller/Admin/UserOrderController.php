<?php

namespace App\Controller\Admin;

use App\Entity\Command;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class UserOrderController extends AbstractController
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
        $qb = $em->getRepository(Command::class)->findBy(['user' => $user], ['createdAt' => 'desc']);

        $orders = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/user/order/index.html.twig', [
            'orders' => $orders,
            'user' => $user
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(EntityManagerInterface $em, $id)
    {
        $order = $em->getRepository(Command::class)->find($id);

        return $this->render('admin/user/order/show.html.twig', [
            'order' => $order,
        ]);
    }
}



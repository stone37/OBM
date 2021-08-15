<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\Settings;
use App\Entity\User;
use App\Form\Filter\SearchType;
use App\Model\Search;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
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
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param User $user
     * @return Response
     */
    public function show(
        Request  $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        User $user): Response
    {
        $search = (new Search())->setUser($user);
        $search = $this->hydrate($request, $search);

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $number = $em->getRepository(Advert::class)->getUserAdvertActiveNumber($search);
        $adverts = $em->getRepository(Advert::class)->getUserAdvertActive($search);

        $adverts = $paginator->paginate($adverts, $request->query->getInt('page', 1), 20);

        return $this->render('user/profil/profil.html.twig', [
            'user' => $user,
            'settings' => $this->settings,
            'number' => $number,
            'adverts' => $adverts,
        ]);
    }

    /**
     * @param Request $request
     * @param Search $search
     * @return Search
     */
    private function hydrate(Request $request, Search $search)
    {
        if ($request->query->has('order'))
            $search->setOrder($request->query->get('order'));

        return $search;
    }



   /* public function invoice(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $orders = $em->getRepository(Order::class)->byFacture($user);

        return $this->render('site/dashboard/order/invoice.html.twig', [
            'orders' => $orders,
            'user' => $user
        ]);
    }

    public function invoicePDF(EntityManagerInterface $em, GetFacture $facture, $id)
    {
        $order = $em->getRepository(Order::class)->findOneBy([
            'user' => $this->getUser(),
            'validate' => 1,
            'id' => $id]);

        if (!$order) {
            $this->addFlash('error', 'Une erreur est survenue');
            //return $this->redirect($this->generateUrl('facutres'));
        }

        //$facture->facture($order)->Output('Invoice.pdf');

        $response = new Response();
        $response->headers->set('Content-type' , 'application/pdf');

        return $response;
    }*/

}


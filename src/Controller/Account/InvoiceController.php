<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Payment;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceController extends AbstractController
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
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $user = $this->getUsers($em, $this->getUser()->getId());
        $payments = $em->getRepository(Payment::class)->findfor($user);

        $payments = $paginator->paginate($payments, $request->query->getInt('page', 1), 20);

        return $this->render('user/invoice/index.html.twig', [
            'settings' => $this->settings,
            'user'     => $user,
            'payments'  => $payments,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param int $id
     */
    public function show(EntityManagerInterface $em, int $id)
    {
        $payment = $em->getRepository(Payment::class)->findForId($id, $this->getUser());

        if (null === $payment) {
            throw new NotFoundHttpException();
        }

        return $this->render('user/invoice/show.html.twig', [
            'payment' => $payment,
            'settings' => $this->settings,
        ]);
    }
}

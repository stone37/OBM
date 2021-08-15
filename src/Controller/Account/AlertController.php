<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Alert;
use App\Entity\Settings;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlertController extends AbstractController
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
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function alert(Request $request, PaginatorInterface $paginator,EntityManagerInterface $em)
    {
        $settings = $this->settings;

        if (!$settings->isActiveAlert())  throw $this->createNotFoundException('Page introuvable');

        $user = $this->getUsers($em, $this->getUser()->getId());
        $alerts = $em->getRepository(Alert::class)->getByUser($user);
        $alerts = $paginator->paginate($alerts, $request->query->getInt('page', 1), 15);

        $alertAN = $em->getRepository(Alert::class)->getUserAlertActiveNumber($user);
        $alertN  = $em->getRepository(Alert::class)->getUserAlertNumber($user);

        return $this->render('user/alert/index.html.twig', [
            'settings' => $settings,
            'user' => $user,
            'alerts' => $alerts,
            'alertAN' => $alertAN,
            'alertN' => $alertN,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function alertActive(Request $request, PaginatorInterface $paginator,EntityManagerInterface $em)
    {
        $settings = $this->settings;

        if (!$settings->isActiveAlert())  throw $this->createNotFoundException('Page introuvable');

        $user = $this->getUsers($em, $this->getUser()->getId());
        $alerts = $em->getRepository(Alert::class)->getEnabledByUser($user);
        $alerts = $paginator->paginate($alerts, $request->query->getInt('page', 1), 15);

        $alertAN = $em->getRepository(Alert::class)->getUserAlertActiveNumber($user);
        $alertN  = $em->getRepository(Alert::class)->getUserAlertNumber($user);

        return $this->render('user/alert/published.html.twig', [
            'settings' => $settings,
            'user' => $user,
            'alerts' => $alerts,
            'alertAN' => $alertAN,
            'alertN' => $alertN,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param EntityManagerInterface $em
     * @param $id
     * @return RedirectResponse
     */
    public function status(EntityManagerInterface $em, $id)
    {
        $alert = $em->getRepository(Alert::class)->find($id);

        if ($alert->isEnabled()) {
            $alert->setEnabled(false);
            $em->flush();

            $this->addFlash('success', 'Votre est maintenant inactive');

            $redirect = $this->redirectToRoute('app_dashboard_alert_index_active');
        } else {
            $alert->setEnabled(true);
            $em->flush();

            $this->addFlash('success', 'Votre est maintenant active');

            $redirect = $this->redirectToRoute('app_dashboard_alert_index');
        }

        return $redirect;
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return JsonResponse|Response|RedirectResponse
     * @throws \Exception
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $alert = $em->getRepository(Alert::class)->find($id);

        $form = $this->deleteForm($alert);

        if ($request->getMethod() == 'DELETE') {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $em->remove($alert);
                $em->flush();

                $this->addFlash('success', 'L\'alerte a été supprimer');
            } else {
                $this->addFlash('error', 'Désolé, l\'alerte n\'a pas pu être supprimer!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette alerte ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $alert,
            'message' => $message,
            'configuration' => $this->deleteConfig(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Alert $alert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Alert $alert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_dashboard_alert_delete', ['id' => $alert->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \string[][][]
     */
    private function deleteConfig()
    {
        return [
            'modal' => [
                'delete' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger',
                ]
            ]
        ];
    }
}

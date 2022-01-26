<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Event\AdminCRUDEvent;
use App\Event\AdvertValidateEvent;
use App\Event\AdvertDeniedEvent;
use App\Form\Filter\AdminAdvertType;
use App\Model\Admin\AdvertSearch;
use App\Service\UserBanService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdvertController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param $type
     * @return Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        $type)
    {
        $search = new AdvertSearch();

        $form = $this->createForm(AdminAdvertType::class, $search, ['em' => $em]);

        $form->handleRequest($request);

        $qb = $em->getRepository(Advert::class)->getAdmin($search, $type);

        $adverts = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/advert/index.html.twig', [
            'adverts' => $adverts,
            'type' => $type,
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $type
     * @param $id
     * @return Response
     */
    public function show(EntityManagerInterface $em, $type, $id)
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        return $this->render('admin/advert/show.html.twig', [
            'advert' => $advert,
            'type' => $type,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return Response
     */
    public function validate(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $id): Response
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->validateForm($advert);

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $advert->setValidated(true);
                $advert->setValidatedAt(new DateTime());
                $advert->setDenied(false);
                $advert->setDeniedAt(null);

                $em->flush();

                $dispatcher->dispatch(new AdvertValidateEvent($advert));

                $this->addFlash('info', 'L\'annonce a été valider');
            } else {
                $this->addFlash('error', 'Désolé, l\'annonce n\'a pas pu être validée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir valider cette annonce ?';

        $render = $this->render('Ui/Modal/_validate.html.twig', [
            'form' => $form->createView(),
            'data' => $advert,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     * @return JsonResponse|RedirectResponse
     */
    public function validateBulk(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    )
    {
        $ids = (array)$request->query->get('data');

        if ($request->query->has('data'))
            $session->set('data', $request->query->get('data'));

        $form = $this->validateMultiForm();

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $ids = $session->get('data');
                $session->remove('data');

                foreach ($ids as $id) {
                    $advert = $em->getRepository(Advert::class)->find($id);

                    $advert->setValidated(true);
                    $advert->setValidatedAt(new DateTime());
                    $advert->setDenied(false);
                    $advert->setDeniedAt(null);

                    $dispatcher->dispatch(new AdvertValidateEvent($advert));
                }

                $em->flush();

                $this->addFlash('info', 'Les annonces ont été valider');
            } else {
                $this->addFlash('error', 'Désolé, les annonces n\'ont pas pu être validée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir valider ces '.count($ids).' annonces ?';
        else
            $message = 'Être vous sur de vouloir valider cette annonce ?';

        $render = $this->render('Ui/Modal/_validate_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function denied(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $id
    )
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->deniedForm($advert);

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $advert->setDenied(true);
                $advert->setDeniedAt(new DateTime());
                $advert->setValidated(false);
                $advert->setValidatedAt(null);

                $em->flush();

                $dispatcher->dispatch(new AdvertDeniedEvent($advert));

                $this->addFlash('info', 'L\'annonce a été refuser');
            } else {
                $this->addFlash('error', 'Désolé, l\'annonce n\'a pas pu être refusée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir refuser cette annonce ?';

        $render = $this->render('Ui/Modal/_denied.html.twig', [
            'form' => $form->createView(),
            'data' => $advert,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     * @return JsonResponse|RedirectResponse
     */
    public function deniedBulk(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    )
    {
        $ids = (array)$request->query->get('data');

        if ($request->query->has('data'))
            $session->set('data', $request->query->get('data'));

        $form = $this->deniedMultiForm();

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $ids = $session->get('data');
                $session->remove('data');

                foreach ($ids as $id) {
                    $advert = $em->getRepository(Advert::class)->find($id);

                    $advert->setDenied(true);
                    $advert->setDeniedAt(new DateTime());
                    $advert->setValidated(false);
                    $advert->setValidatedAt(null);

                    $dispatcher->dispatch(new AdvertDeniedEvent($advert));
                }

                $em->flush();

                $this->addFlash('info', 'Les annonces ont été refuser');
            } else {
                $this->addFlash('error', 'Désolé, les annonces n\'ont pas pu être refusée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir refuser ces '.count($ids).' annonces ?';
        else
            $message = 'Être vous sur de vouloir refuser cette annonce ?';

        $render = $this->render('Ui/Modal/_denied_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->deleteForm($advert);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($advert);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($advert);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'L\'annonce a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, l\'annonce n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette annonce ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $advert,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param EventDispatcherInterface $dispatcher
     * @return JsonResponse|RedirectResponse
     */
    public function deleteBulk(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    )
    {
        $ids = (array)$request->query->get('data');

        if ($request->query->has('data'))
            $session->set('data', $request->query->get('data'));

        $form = $this->deleteMultiForm();

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $ids = $session->get('data');
                $session->remove('data');

                foreach ($ids as $id) {
                    $advert = $em->getRepository(Advert::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($advert), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($advert);
                }

                $em->flush();

                $this->addFlash('info', 'Les annonces ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les annonces n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' annonces ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette annonce ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function softDelete(
        Request $request,
        EntityManagerInterface $em,
        $id
    )
    {
        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->deleteSoftForm($advert);

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $advert->setDeleted(true);
                $advert->setDeletedAt(new DateTime());

                $em->flush();

                $this->addFlash('info', 'L\'annonce a été retiré');
            } else {
                $this->addFlash('error', 'Désolé, l\'annonce n\'a pas pu être retirée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir retirer cette annonce ?';

        $render = $this->render('Ui/Modal/_soft_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $advert,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserBanService $service
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function banned(
        Request $request,
        EntityManagerInterface $em,
        UserBanService $service,
        $id
    )
    {
        /** @var Advert $advert */
        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->bannirForm($advert);

        if ($request->getMethod() == 'PUT') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $service->ban($advert->getUser());

                $this->addFlash('info', 'L\'annonceur a été banni');
            } else {
                $this->addFlash('error', 'Désolé, l\'annonceur n\'a pas pu être banni !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir bannir cet annonceur ?';

        $render = $this->render('Ui/Modal/_bannir.html.twig', [
            'form' => $form->createView(),
            'data' => $advert,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $type
     * @return JsonResponse|RedirectResponse
     */
    public function clean(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $type)
    {
        if ($type === 1) {
            $adverts = $em->getRepository(Advert::class)->getDenied();
        } elseif ($type === 2) {
            $adverts = $em->getRepository(Advert::class)->getExpired();
        } else {
            $adverts = $em->getRepository(Advert::class)->getDeleted();
        }

        $form = $this->createFormBuilder()
                    ->setAction($this->generateUrl('app_admin_advert_clean', ['type' => $type]))
                    ->setMethod('DELETE')->getForm();

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                foreach ($adverts as $advert) {

                    $dispatcher->dispatch(new AdminCRUDEvent($advert), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($advert);
                }

                $em->flush();

                $this->addFlash('info', 'Les annonces ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les annonces n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($adverts) > 0) {
            if (count($adverts) > 1) {
                $message = 'Être vous sur de vouloir supprimer ces '.count($adverts).' annonces ?';
            } else {
                $message = 'Être vous sur de vouloir supprimer cette annonce ?';
            }

            $render = $this->render('Ui/Modal/_clean.html.twig', [
                'form' => $form->createView(),
                'data' => $adverts,
                'message' => $message,
                'configuration' => $this->configuration(),
            ]);

            $response['html'] = $render->getContent();
            $response['status'] = true;

            return new JsonResponse($response);
        } else {

            $response['html'] = "";
            $response['status'] = false;

            return new JsonResponse($response);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @return JsonResponse|RedirectResponse
     */
    public function reload(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher)
    {
        $adverts = $em->getRepository(Advert::class)->getExpired();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_reload'))
            ->setMethod('POST')
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                foreach ($adverts as $advert) {

                    $dispatcher->dispatch(new AdminCRUDEvent($advert), AdminCRUDEvent::PRE_EDIT);

                    $em->remove($advert);
                }

                $em->flush();

                $this->addFlash('info', 'Les annonces ont été relancée');
            } else {
                $this->addFlash('error', 'Désolé, les annonces n\'ont pas pu être relancée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($adverts) > 0) {
            if (count($adverts) > 1) {
                $message = 'Être vous sur de vouloir relancer ces '.count($adverts).' annonces ?';
            } else {
                $message = 'Être vous sur de vouloir relancer cette annonce ?';
            }

            $render = $this->render('Ui/Modal/_reload.html.twig', [
                'form' => $form->createView(),
                'data' => $adverts,
                'message' => $message,
                'configuration' => $this->configuration(),
            ]);

            $response['html'] = $render->getContent();
            $response['status'] = true;

            return new JsonResponse($response);
        } else {

            $response['html'] = "";
            $response['status'] = false;

            return new JsonResponse($response);
        }
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_delete', ['id' => $advert->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_bulk_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function validateForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_validate', ['id' => $advert->getId()]))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function validateMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_bulk_validate'))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deniedForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_denied', ['id' => $advert->getId()]))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deniedMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_bulk_denied'))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteSoftForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_soft_delete', ['id' => $advert->getId()]))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function bannirForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_advert_banned', ['id' => $advert->getId()]))
            ->setMethod('PUT')
            ->getForm();
    }

    /**
     * @return \string[][][]
     */
    private function configuration()
    {
        return [
            'modal' => [
                'delete' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger'
                ],
                'validate' => [
                    'type' => 'modal-success',
                    'icon' => 'fas fa-reply',
                    'yes_class' => 'btn-outline-success',
                    'no_class' => 'btn-success'
                ],
                'denied' => [
                    'type' => 'modal-amber',
                    'icon' => 'fas fa-share',
                    'yes_class' => 'btn-outline-amber',
                    'no_class' => 'btn-amber'
                ],
                'soft' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger'
                ],
                'bannir' => [
                    'type' => 'modal-default',
                    'icon' => 'fas fa-bug',
                    'yes_class' => 'btn-outline-default',
                    'no_class' => 'btn-default'
                ]
            ]
        ];
    }
}



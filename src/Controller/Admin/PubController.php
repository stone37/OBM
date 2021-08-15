<?php

namespace App\Controller\Admin;

use App\Entity\Pub;
use App\Event\AdminCRUDEvent;
use App\Form\Filter\AdminPubType;
use App\Form\PubHomeType;
use App\Form\PubType;
use App\Model\Admin\PubSearch;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PubController extends AbstractController
{
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        $type
    )
    {
        $search = new PubSearch();
        $form = $this->createForm(AdminPubType::class, $search);

        $form->handleRequest($request);
        $qb = $em->getRepository(Pub::class)->getAdmin($search, $type);

        $pubs = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/pub/index.html.twig', [
            'pubs' => $pubs,
            'type'   => $type,
            'searchForm' => $form->createView(),
        ]);
    }

    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $type
    )
    {
        $pub = (new Pub())
            ->setType($type)
            ->setEnabled(true);

        if ($type == 1 or $type == 2) {
            $form = $this->createForm(PubHomeType::class, $pub);
        } else {
            $form = $this->createForm(PubType::class, $pub);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($pub);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($pub);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('success', 'Une publicité a été crée');

            return $this->redirectToRoute('app_admin_pub_index', ['type' => $type]);
        }

        return $this->render('admin/pub/create.html.twig', [
            'form' => $form->createView(),
            'type' => $type,
        ]);
    }

    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id, $type
    )
    {
        $pub = $em->getRepository(Pub::class)->find($id);

        if ($type == 1 or $type == 2) {
            $form = $this->createForm(PubHomeType::class, $pub);
        } else {
            $form = $this->createForm(PubType::class, $pub);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($pub);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'La publicité a été mise à jour');

            return $this->redirectToRoute('app_admin_pub_index', ['type' => $type]);
        }

        return $this->render('admin/pub/edit.html.twig', [
            'form' => $form->createView(),
            'pub' => $pub,
            'type' => $type
        ]);
    }

    public function move(
        Request $request,
        EntityManagerInterface $em,
        $id, $type
    )
    {
        $pub = $em->getRepository(Pub::class)->find($id);

        if ($request->query->has('pos')) {
            $pos = ($pub->getPosition() + (int)$request->query->get('pos'));

            if ($pos >= 0) {
                $pub->setPosition($pos);
                $em->flush();

                $this->addFlash('info', 'La position de la publicité a été modifier');
            }
        }

        return $this->redirectToRoute('app_admin_pub_index', ['type' => $type]);
    }

    public function delete(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id
    )
    {
        $pub = $em->getRepository(Pub::class)->find($id);

        $form = $this->deleteForm($pub);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($pub);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($pub);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'La publicité a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, la publicité n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette publicité ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $pub,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    public function deleteBulk(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        SessionInterface $session
    )
    {
        $ids = $request->query->get('data');

        if ($request->query->has('data'))
            $session->set('data', $request->query->get('data'));

        $form = $this->deleteMultiForm();

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $ids = $session->get('data');
                $session->remove('data');

                foreach ($ids as $id) {
                    $pub = $em->getRepository(Pub::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($pub), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($pub);
                }

                $em->flush();

                $this->addFlash('info', 'Les publicités ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les publicités n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' publicités ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette publicité ?';

        $render = $this->render('Ui/Modal/_delete_multi.html.twig', [
            'form' => $form->createView(),
            'data' => $ids,
            'message' => $message,
            'configuration' => $this->configuration(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    private function deleteForm(Pub $pub)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_pub_delete', ['id' => $pub->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_pub_bulk_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function configuration()
    {
        return [
            'modal' => [
                'delete' => [
                    'type' => 'modal-danger',
                    'icon' => 'fas fa-times',
                    'yes_class' => 'btn-outline-danger',
                    'no_class' => 'btn-danger'
                ]
            ]
        ];
    }
}


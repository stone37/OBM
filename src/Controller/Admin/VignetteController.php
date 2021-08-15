<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Event\AdminCRUDEvent;
use App\Form\AdminVignetteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VignetteController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $qb = $em->getRepository(Product::class)->getAdmin(8);

        $vignettes = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/vignette/index.html.twig', [
            'vignettes' => $vignettes,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @return RedirectResponse|Response
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher)
    {
        $vignette = new Product();
        $vignette->setCategory('v');
        $vignette->setType(8);
        $form = $this->createForm(AdminVignetteType::class, $vignette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($vignette);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($vignette);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('info', 'Une vignette a été crée');

            return $this->redirectToRoute('app_admin_vignette_index');
        }

        return $this->render('admin/vignette/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return Response
     */
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher, $id): Response
    {
        $vignette = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(AdminVignetteType::class, $vignette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($vignette);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Une vignette a été mise à jour');

            return $this->redirectToRoute('app_admin_vignette_index');
        }

        return $this->render('admin/vignette/edit.html.twig', [
            'form' => $form->createView(),
            'vignette' => $vignette,
        ]);
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
        $vignette = $em->getRepository(Product::class)->find($id);

        $form = $this->deleteForm($vignette);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($vignette);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($vignette);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'Une vignette a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, la vignette n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette vignette ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $vignette,
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
                    $vignette = $em->getRepository(Product::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($vignette), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($vignette);
                }

                $em->flush();

                $this->addFlash('info', 'Les vignettes ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les vignettes n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' vignettes ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette vignette ?';

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
     * @param Product $pack
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Product $vignette)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_vignette_delete', ['id' => $vignette->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_vignette_bulk_delete'))
            ->setMethod('DELETE')
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
                ]
            ]
        ];
    }
}



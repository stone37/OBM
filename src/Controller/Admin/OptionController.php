<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Event\AdminCRUDEvent;
use App\Form\OptionVisualType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OptionController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        $type
    )
    {
        $qb = $em->getRepository(Product::class)->getAdmin($type);

        $options = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/option/index.html.twig', [
            'options' => $options,
            'type'   => $type,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $type
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $type
    )
    {
        $option = new Product();
        $option->setType($type);
        $option->setCategory('ov');

        $form = $this->createForm(OptionVisualType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($option);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($option);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('success', 'Une option visuelle a été crée');

            return $this->redirectToRoute('app_admin_option_index', ['type' => $type]);
        }

        return $this->render('admin/option/create.html.twig', [
            'form' => $form->createView(),
            'type' => $type,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @param $type
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id, $type
    )
    {
        $option = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(OptionVisualType::class, $option);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($option);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Une option visuelle a été mise à jour');

            return $this->redirectToRoute('app_admin_option_index', ['type' => $type]);
        }

        return $this->render('admin/option/edit.html.twig', [
            'form' => $form->createView(),
            'option' => $option,
            'type' => $type
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
        $id
    )
    {
        $option = $em->getRepository(Product::class)->find($id);

        $form = $this->deleteForm($option);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($option);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($option);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'L\'option visuelle a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, l\'option visuelle n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette option visuelle ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $option,
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
     * @param SessionInterface $session
     * @return JsonResponse|RedirectResponse
     */
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
                    $option = $em->getRepository(Product::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($option), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($option);
                }

                $em->flush();

                $this->addFlash('info', 'Les options visuelles ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les options visuelles n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' options visuelles ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette option visuelle ?';

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
     * @param Product $option
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Product $option)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_option_delete', ['id' => $option->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_option_bulk_delete'))
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


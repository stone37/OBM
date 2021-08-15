<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Event\AdminCRUDEvent;
use App\Form\CategoryType;
use App\Form\Filter\AdminCategoryType;
use App\Model\Admin\CategorySearch;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    )
    {
        $search = new CategorySearch();
        $form = $this->createForm(AdminCategoryType::class, $search);

        $form->handleRequest($request);

        if ($request->query->has('parentId'))
            $qb = $em->getRepository(Category::class)->getAdminWithParent($search, $request->query->get('parentId'));
        else
            $qb = $em->getRepository(Category::class)->getAdminWithParent($search);

        $categories = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        $parent = ($request->query->has('parentId')) ? $em->getRepository(Category::class)->find($request->query->get('parentId')): null;

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'parentId' => $request->query->get('parentId'),
            'parent'   => $parent,
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    )
    {
        $parent = null;

        if ($request->query->has('parentId'))
            $parent = $em->getRepository(Category::class)->find($request->query->get('parentId'));

        $category = new Category();
        $category->setParent($parent);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($category);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($category);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('success', 'Une catégorie a été crée');

            $redirect = ($request->query->has('parentId')) ? $this->redirectToRoute('app_admin_category_index', ['parentId' => $request->query->get('parentId')]) : $this->redirectToRoute('app_admin_category_index');

            return $redirect;
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
            'parent' => $parent,
            'parentId' => $request->query->get('parentId'),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id
    )
    {
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($category);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Une catégorie a été mise à jour');

            $redirect = ($request->query->has('parentId')) ? $this->redirectToRoute('app_admin_category_index', ['parentId' => $request->query->get('parentId')]) : $this->redirectToRoute('app_admin_category_index');

            return $redirect;
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
            'parent' => $category->getParent(),
            'parentId' => $request->query->get('parentId'),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $id
     * @return RedirectResponse
     */
    public function move(
        Request $request,
        EntityManagerInterface $em,
        $id
    )
    {
        $category = $em->getRepository(Category::class)->find($id);

        if ($request->query->has('pos')) {
            $pos = ($category->getPosition() + (int)$request->query->get('pos'));

            if ($pos >= 0) {
                $category->setPosition($pos);
                $em->flush();

                $this->addFlash('info', 'La position a été modifier');
            }
        }

        $redirect = ($request->query->has('parentId')) ? $this->redirectToRoute('app_admin_category_index', ['parentId' => $request->query->get('parentId')]) : $this->redirectToRoute('app_admin_category_index');

        return $redirect;
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
        $category = $em->getRepository(Category::class)->find($id);

        $form = $this->deleteForm($category);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($category);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($category);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'La catégorie a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, la catégorie n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette catégorie ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $category,
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
                    $category = $em->getRepository(Category::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($category), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($category);
                }

                $em->flush();

                $this->addFlash('info', 'Les catégories ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les catégories n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' catégories ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette catégorie ?';

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
     * @param Category $category
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_category_delete', ['id' => $category->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_category_bulk_delete'))
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


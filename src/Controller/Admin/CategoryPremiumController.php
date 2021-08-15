<?php

namespace App\Controller\Admin;

use App\Entity\CategoryPremium;
use App\Event\AdminCRUDEvent;
use App\Form\CategoryPremiumType;
use App\Form\Filter\AdminCategoryType;
use App\Model\Admin\CategorySearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryPremiumController extends AbstractController
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
        $search = new CategorySearch();

        $form = $this->createForm(AdminCategoryType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(CategoryPremium::class)->getAdmin($search);

        $categories = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/categoryP/index.html.twig', [
            'categories' => $categories,
            'searchForm' => $form->createView(),
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
        $category = new CategoryPremium();
        $form = $this->createForm(CategoryPremiumType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($category);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_CREATE);

            $em->persist($category);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_CREATE);

            $this->addFlash('info', 'Une catégorie premium a été crée');

            return $this->redirectToRoute('app_admin_category_premium_index');
        }

        return $this->render('admin/categoryP/create.html.twig', [
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
        $category = $em->getRepository(CategoryPremium::class)->find($id);
        $form = $this->createForm(CategoryPremiumType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($category);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Une catégorie premium a été mise à jour');

            return $this->redirectToRoute('app_admin_category_premium_index');
        }

        return $this->render('admin/categoryP/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
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
        $category = $em->getRepository(CategoryPremium::class)->find($id);

        if ($request->query->has('pos')) {
            $pos = ($category->getPosition() + (int)$request->query->get('pos'));

            if ($pos >= 0) {
                $category->setPosition($pos);
                $em->flush();

                $this->addFlash('info', 'La position a été modifier');
            }
        }

        return $this->redirectToRoute('app_admin_category_premium_index');
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
        $category = $em->getRepository(CategoryPremium::class)->find($id);

        $form = $this->deleteForm($category);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($category);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($category);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

                $this->addFlash('info', 'La catégorie premium a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, la catégorie premium n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cette catégorie premium ?';

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
                    $category = $em->getRepository(CategoryPremium::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($category), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($category);
                }

                $em->flush();

                $this->addFlash('info', 'Les catégories premium ont été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les catégories premium n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' catégories premium ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette catégorie premium ?';

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
     * @param CategoryPremium $category
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(CategoryPremium $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_category_premium_delete', ['id' => $category->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_category_premium_bulk_delete'))
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


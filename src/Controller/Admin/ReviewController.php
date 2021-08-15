<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use App\Event\AdminCRUDEvent;
use App\Form\Filter\AdminReviewType;
use App\Form\ReviewAdminType;
use App\Model\Admin\ReviewSearch;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class ReviewController extends AbstractController
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
        $search = new ReviewSearch();

        $form = $this->createForm(AdminReviewType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Review::class)->getAdminReviews($search, Review::REVIEW);

        $reviews = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/review/index.html.twig', [
            'reviews' => $reviews,
            'type' => Review::REVIEW,
            'searchForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function suggestion(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator)
    {
        $search = new ReviewSearch();

        $form = $this->createForm(AdminReviewType::class, $search);

        $form->handleRequest($request);

        $qb = $em->getRepository(Review::class)->getAdminReviews($search, Review::SUBJECT);

        $reviews = $paginator->paginate($qb, $request->query->getInt('page', 1), 25);

        return $this->render('admin/review/index.html.twig', [
            'reviews' => $reviews,
            'type' => Review::SUBJECT,
            'searchForm' => $form->createView(),
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
        $review = $em->getRepository(Review::class)->find($id);
        $form = $this->createForm(ReviewAdminType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($review);

            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            $this->addFlash('info', 'Un avis a été mise à jour');

            return $this->redirectToRoute('app_admin_review_index');
        }

        return $this->render('admin/review/edit.html.twig', [
            'form' => $form->createView(),
            'review' => $review,
            'type' => Review::REVIEW
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
        $review = $em->getRepository(Review::class)->find($id);

        $form = $this->deleteForm($review);

        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdminCRUDEvent($review);

                $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

                $em->remove($review);
                $em->flush();

                $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);


                $this->addFlash('info', 'L\'element a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, l\'element n\'a pas pu être supprimée!');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        $message = 'Être vous sur de vouloir supprimer cet element ?';

        $render = $this->render('Ui/Modal/_delete.html.twig', [
            'form' => $form->createView(),
            'data' => $review,
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
                    $review = $em->getRepository(Review::class)->find($id);
                    $dispatcher->dispatch(new AdminCRUDEvent($review), AdminCRUDEvent::PRE_DELETE);

                    $em->remove($review);
                }

                $em->flush();

                $this->addFlash('info', 'Les elements a été supprimé');
            } else {
                $this->addFlash('error', 'Désolé, les elements n\'ont pas pu être supprimée !');
            }

            $url = $request->request->get('referer');

            $response = new RedirectResponse($url);

            return $response;
        }

        if (count($ids) > 1)
            $message = 'Être vous sur de vouloir supprimer ces '.count($ids).' elements ?';
        else
            $message = 'Être vous sur de vouloir supprimer cette element ?';

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
     * @param Review $review
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Review $review)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_review_delete', ['id' => $review->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    private function deleteMultiForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_admin_review_bulk_delete'))
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


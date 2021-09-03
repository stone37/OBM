<?php

namespace App\Controller\Account;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Advert;
use App\Entity\AdvertPicture;
use App\Entity\Product;
use App\Entity\Settings;
use App\Event\AdvertBadEvent;
use App\Event\AdvertCreatedInitializeEvent;
use App\Event\AdvertDeleteEvent;
use App\Event\AdvertEditEvent;
use App\Event\AdvertImageEditEvent;
use App\Event\AdvertImagePreEditEvent;
use App\Event\AdvertPreDeleteEvent;
use App\Event\AdvertPreEditEvent;
use App\Form\Filter\SearchType;
use App\Manager\AdvertManager;
use App\Model\Search;
use App\Service\SettingsManager;
use App\Storage\OrderSessionStorage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Class AdvertController
 * @package App\Controller\Account
 */
class AdvertController extends AbstractController
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
     * @param OrderSessionStorage $storage
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advert(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        OrderSessionStorage $storage,
        PaginatorInterface $paginator)
    {
        $settings = $this->settings;

        $storage->remove();
        $session->set('app_cart', []);

        $search = (new Search())->setUser($this->getUser());

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $advertAN = $em->getRepository(Advert::class)->getUserAdvertActiveNumber($search);
        $advertN  = $em->getRepository(Advert::class)->getUserAdvertNumber($search);
        $data = $em->getRepository(Advert::class)->getUserAdvert($search);

        $adverts = $paginator->paginate($data, $request->query->getInt('page', 1), $settings->getNumberAdUserList());

        $gallery = $em->getRepository(Product::class)->getOptionVisualByType(3);
        $urgents = $em->getRepository(Product::class)->getOptionVisualByType(2);
        $headers = $em->getRepository(Product::class)->getOptionVisualByType(1);
        $vedettes = $em->getRepository(Product::class)->getOptionVisualByType(4);
        $encadres = $em->getRepository(Product::class)->getOptionVisualByType(5);
        $optionPhoto = $em->getRepository(Product::class)->getOptionPhoto();

        return $this->render('user/advert/index.html.twig', [
            'settings' => $settings,
            'user'     => $this->getUsers($em, $this->getUser()->getId()),
            'advertN'  => $advertN,
            'advertAN' => $advertAN,
            'adverts'  => $adverts,
            'gallery' => $gallery,
            'urgents' => $urgents,
            'headers' => $headers,
            'vedettes' => $vedettes,
            'encadres' => $encadres,
            'photo' => $optionPhoto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Liste des annonces actives d'un utilisateur
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Breadcrumbs $breadcrumbs
     * @param PaginatorInterface $paginator
     * @param SettingsManager $sm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advertPublished(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        OrderSessionStorage $storage,
        PaginatorInterface $paginator)
    {
        $settings = $this->settings;

        $storage->remove();
        $session->set('app_cart', []);

        $search = (new Search())->setUser($this->getUser());

        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $advertAN = $em->getRepository(Advert::class)->getUserAdvertActiveNumber($search);
        $advertN  = $em->getRepository(Advert::class)->getUserAdvertNumber($search);
        $data = $em->getRepository(Advert::class)->getUserAdvertActive($search);

        $gallery = $em->getRepository(Product::class)->getOptionVisualByType(3);
        $urgents = $em->getRepository(Product::class)->getOptionVisualByType(2);
        $headers = $em->getRepository(Product::class)->getOptionVisualByType(1);
        $vedettes = $em->getRepository(Product::class)->getOptionVisualByType(4);
        $encadres = $em->getRepository(Product::class)->getOptionVisualByType(5);
        $optionPhoto = $em->getRepository(Product::class)->getOptionPhoto();

        $adverts = $paginator->paginate($data, $request->query->getInt('page', 1), $settings->getNumberAdUserList());

        return $this->render('user/advert/published.html.twig', [
            'settings' => $settings,
            'user'     => $this->getUsers($em, $this->getUser()->getId()),
            'advertN'  => $advertN,
            'advertAN' => $advertAN,
            'adverts'  => $adverts,
            'gallery' => $gallery,
            'urgents' => $urgents,
            'headers' => $headers,
            'vedettes' => $vedettes,
            'encadres' => $encadres,
            'photo' => $optionPhoto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param AdvertManager $manager
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        OrderSessionStorage $storage,
        AdvertManager $manager,
        $id)
    {
        $storage->remove();

        $advert = $em->getRepository(Advert::class)->find($id);

        $dispatcher->dispatch(new AdvertCreatedInitializeEvent($advert, $request));

        $form = $this->createForm($manager->createFormEditType(), $advert);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $event = new AdvertPreEditEvent($advert, $request);

            $dispatcher->dispatch($event);

            $em->flush();

            if (null === $response = $event->getResponse()) {
                $route = $advert->isValidated() ?
                    $this->generateUrl('app_dashboard_advert_index_active') :
                    $this->generateUrl('app_dashboard_advert_index');

                $response = new RedirectResponse($route);
            }

            $dispatcher->dispatch(new AdvertEditEvent($advert, $request));

            return $response;
        } else {
            $dispatcher->dispatch(new AdvertBadEvent($advert, $request));
        }

        return $this->render('user/advert/edit.html.twig', [
            'form' => $form->createView(),
            'view' => $manager->createFormViewRoute(),
            'advert' => $advert,
            'settings' => $this->settings,
        ]);

    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @param $id
     * @return RedirectResponse|Response|null
     */
    public function image(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id
    )
    {
        $advert = $em->getRepository(Advert::class)->find($id);
        $option = $em->getRepository(Product::class)->findOneBy(['category' => 'OP']);

        $dispatcher->dispatch(new AdvertCreatedInitializeEvent($advert, $request));

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_dashboard_advert_image_add', ['id' => $advert->getId()]))
            ->setMethod('POST')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdvertImagePreEditEvent($advert, $request);

            $dispatcher->dispatch($event);

            $em->flush();

            if (null === $response = $event->getResponse()) {
                $route = $advert->isValidated() ?
                    $this->generateUrl('app_dashboard_advert_index_active') :
                    $this->generateUrl('app_dashboard_advert_index');

                $response = new RedirectResponse($route);
            }

            $dispatcher->dispatch(new AdvertImageEditEvent($advert, $request));

            return $response;
        } else {
            $dispatcher->dispatch(new AdvertBadEvent($advert, $request));
        }

        return $this->render('user/advert/image/add.html.twig', [
            'form' => $form->createView(),
            'advert' => $advert,
            'option' => $option,
            'settings' => $this->settings,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AdvertPicture $picture
     * @return JsonResponse
     */
    public function imageDelete(Request $request, EntityManagerInterface $em, AdvertPicture $picture)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Mauvais requête');

        $picture->setAdvert(null);
        $id = $picture->getId();

        $em->remove($picture);
        $em->flush();

        return new JsonResponse(['id' => $id]);
    }

    /**
     * @IsGranted("ROLE_USER")
     *
     * @param SessionInterface $session
     * @param $id
     * @return JsonResponse
     */
    public function imagePrincipale(SessionInterface $session, $id)
    {
        $session->set('app_image_pos', $id);

        return new JsonResponse(['id' => $id]);
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
        EventDispatcherInterface $dispatcher,
        $id)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $advert = $em->getRepository(Advert::class)->find($id);

        $form = $this->deleteForm($advert);

        if ($request->getMethod() == 'DELETE') {

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $event = new AdvertPreDeleteEvent($advert);

                $dispatcher->dispatch($event);

                if (null !== $response = $event->getResponse()) return $response;

                $advert->setDeleted(true);
                $advert->setDeletedAt(new DateTime());
                $em->flush();

                $dispatcher->dispatch(new AdvertDeleteEvent($advert));

                if (null !== $response = $event->getResponse()) {
                    return $response;
                }

                $this->addFlash('success', 'L\'annonce a été supprimé');
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
            'configuration' => $this->deleteConfig(),
        ]);

        $response['html'] = $render->getContent();

        return new JsonResponse($response);
    }

    /**
     * @param Advert $advert
     * @return \Symfony\Component\Form\FormInterface
     */
    private function deleteForm(Advert $advert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_dashboard_advert_delete', ['id' => $advert->getId()]))
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

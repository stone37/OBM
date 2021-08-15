<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Event\AdminCRUDEvent;
use App\Form\OptionPhotoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class OptionPhotoController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    )
    {
        $option = $em->getRepository(Product::class)->getOptionPhoto();

        if (null === $option) {
            $option = new Product();
            $option->setCategory('op');
            $option->setType(0);
            $option->setEnabled(true);
        }

        $form = $this->createForm(OptionPhotoType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new AdminCRUDEvent($option);
            $dispatcher->dispatch($event, AdminCRUDEvent::PRE_EDIT);

            $em->persist($option);
            $em->flush();

            $dispatcher->dispatch($event, AdminCRUDEvent::POST_EDIT);

            return $this->redirectToRoute('app_admin_option_photo_index');
        }

        return $this->render('admin/optionPhoto/index.html.twig', [
            'form' => $form->createView(),
            'option' => $option,
        ]);
    }
}


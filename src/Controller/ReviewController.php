<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Form\SuggestionType;
use App\Service\SettingsManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param SettingsManager $manager
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        SettingsManager $manager,
        ReCaptcha $reCaptcha)
    {
        $review = (new Review())->setType(Review::REVIEW);

        if ($this->getUser()) {
            $review->setEmail($this->getUser()->getEmail());
            if ($this->getUser()->getUsername()) {
                $review->setName($this->getUser()->getUsername());
            } else {
                $review->setName($this->getUser()->getFirstName());
            }
        }

        $form = $this->createForm(ReviewType::class, $review, [
            'action' => $this->generateUrl('app_review_index')
        ]);

        $qb = $em->getRepository(Review::class)->getEnabled();
        $reviews = $paginator->paginate($qb, $request->query->getInt('page', 1), 30);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($reCaptcha->verify($form['recaptchaToken']->getData())->isSuccess()) {
                $em->persist($review);
                $em->flush();

                $this->addFlash('success', 'Merci pour votre témoignage');
            } else {
                $this->addFlash('error', 'Erreur pendant l\'envoi de votre témoignage');
            }

            return $this->redirectToRoute('app_review_index');
        }

        return $this->render('site/avis/index.html.twig', [
            'form' => $form->createView(),
            'reviews' => $reviews,
            'settings' => $manager->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function suggestion(Request $request, EntityManagerInterface $em)
    {
        $review = (new Review())->setType(Review::SUBJECT);

        if ($this->getUser()) {
            $review->setEmail($this->getUser()->getEmail());
            if ($this->getUser()->getUsername()) {
                $review->setName($this->getUser()->getUsername());
            } else {
                $review->setName($this->getUser()->getFirstName());
            }
        }

        $form = $this->createForm(SuggestionType::class, $review, [
            'action' => $this->generateUrl('app_suggestion_index')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Merci pour la suggestion');

            $url = $request->request->get('referer');
            $response = new RedirectResponse($url);

            return $response;
        }

        return $this->render('site/avis/suggestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

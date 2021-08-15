<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Event\AlertEvent;
use App\Manager\AlertManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AlertController extends AbstractController
{
    use ControllerTrait;

    /**
     * Créer une alerte
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param EventDispatcherInterface $dispatcher
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        AlertManager $manager,
        EventDispatcherInterface $dispatcher)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');

        $category = $manager->find($request->query->get('category'));
        $subCategory = $manager->find($request->query->get('subCategory'));
        $subDivision = $manager->find($request->query->get('subDivision'));

        if ($manager->hasAlert($category, $subCategory, $subDivision)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Vous avez deja crée une alerte de ce type',
            ]);
        }

        $alert = $manager->createAlert($category, $subCategory, $subDivision);

        $em->persist($alert);
        $em->flush();

        $dispatcher->dispatch(new AlertEvent($alert, $request), AlertEvent::CREATE);

        return new JsonResponse([
            'success' => true,
            'message' => 'Félicitations ! Votre alerte a été activée. Vous devriez bientôt commencer à recevoir des courriels.',
        ]);
    }
}


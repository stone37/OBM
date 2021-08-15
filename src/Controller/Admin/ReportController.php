<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Event\AdminCRUDEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ReportController extends AbstractController
{
    public function delete(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher,
        $id)
    {
        $report = $em->getRepository(Report::class)->find($id);

        $event = new AdminCRUDEvent($report);
        $dispatcher->dispatch($event, AdminCRUDEvent::PRE_DELETE);

        $em->remove($report);
        $em->flush();

        $dispatcher->dispatch($event, AdminCRUDEvent::POST_DELETE);

        $this->addFlash('success', 'Le signalement a été supprimé');

        return $this->redirectToRoute('app_admin_dashboard');
    }
}


<?php

namespace App\Controller\Admin;


use App\Exporter\MaintenanceConfigurationExporter;
use App\Factory\MaintenanceConfigurationFactory;
use App\Form\MaintenanceConfigurationType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class MaintenanceController extends AbstractController
{
    private FlashBagInterface $flashBag;

    private MaintenanceConfigurationExporter $maintenanceExporter;

    private MaintenanceConfigurationFactory $configurationFactory;

    public function __construct(
        FlashBagInterface $flashBag,
        MaintenanceConfigurationExporter $maintenanceExporter,
        MaintenanceConfigurationFactory $configurationFactory
    ) {
        $this->flashBag = $flashBag;
        $this->maintenanceExporter = $maintenanceExporter;
        $this->configurationFactory = $configurationFactory;
    }

    public function index(Request $request): Response
    {
        $maintenanceConfiguration = $this->configurationFactory->get();

        $form = $this->createForm(MaintenanceConfigurationType::class, $maintenanceConfiguration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $maintenanceConfiguration->getEndDate() && $maintenanceConfiguration->getEndDate() < (new DateTime())) {
                $maintenanceConfiguration->setEnabled(false);
                $this->flashBag->add('error', 'La date de fin est dans le passé, la maintenance a été désactivée.');
            }

            $this->maintenanceExporter->export($maintenanceConfiguration);
            $message = 'Le plugin de maintenance a été désactivé avec succès.';
            if ($maintenanceConfiguration->isEnabled()) {
                $message = 'Le plugin de maintenance a été activé avec succès.';
            }

            $this->flashBag->add('success', $message);
        }

        return $this->render('admin/maintenance/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


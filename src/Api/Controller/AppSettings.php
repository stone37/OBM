<?php

namespace App\Api\Controller;

use App\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SettingsManager;

class AppSettings extends AbstractController
{
    private SettingsManager $manager;

    public function __construct(SettingsManager $manager) {
        $this->manager = $manager;
    }
    public function __invoke(Request $request): Settings
    {
        return $this->manager->get();
    }
}



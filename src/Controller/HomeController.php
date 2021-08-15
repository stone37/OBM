<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Service\SettingsManager;
use App\Storage\OrderSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    use ControllerTrait;

    private $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings->get();
    }

    public function index(OrderSessionStorage $storage)
    {
        $storage->remove();

        return $this->render('site/home/index.html.twig', [
            'settings' => $this->settings
        ]);
    }

}

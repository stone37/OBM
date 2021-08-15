<?php

namespace App\Twig;

use App\Entity\Advert;
use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdvertActiveExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('adActive', array($this, 'check'))
        );
    }

    public function check(Advert $advert)
    {
        $date = new DateTime();
        $date->modify('-6 month');

        return ($advert->getValidatedAt() >= $date);
    }
}
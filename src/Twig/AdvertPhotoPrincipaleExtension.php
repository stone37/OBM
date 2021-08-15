<?php

namespace App\Twig;

use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdvertPhotoPrincipaleExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return array(
            new TwigFunction('app_advert_photo', array($this, 'principale')),
        );
    }

    /**
     * @param PersistentCollection $images
     * @return mixed|null
     */
    public function principale(PersistentCollection $images)
    {
        if (!$images) return null;

        $data = null;

        foreach ($images as $image) {
            if ($image->isPrincipale()) {
                $data = $image;
            }
        }

        if (!$data) $data = $images->first();

        return $data;
    }
}


<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigProductDataExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(new TwigFunction('app_product_data', array($this, 'getData')));
    }

    public function getData($type)
    {
        if ($type == 1) {
            return [
                'name' => 'Annonce en tête de liste',
                'url' => 'images/option/1.png',
                'description' => 'Replace votre annonce en tête de liste et
                 gardez une longueur d’avance sur les autres vendeurs'
            ];
        } elseif ($type == 2) {
            return [
                'name' => 'Logo urgent',
                'url' => 'images/option/2.png',
                'description' => 'Faites connaître votre intention de vendre rapidement.'
            ];
        } elseif ($type == 3) {
            return [
                'name' => 'Galerie de la page d\'accueil',
                'url' => 'images/option/3.png',
                'description' => 'Profitez en moyenne de plusieurs impressions par semaine grâce à un 
                positionnement de choix sur notre page d\'accueil.'
            ];
        } elseif ($type == 4) {
            return [
                'name' => 'Annonce en vedette',
                'url' => 'images/option/4.png',
                'description' => 'Les annonces avec l\'option 
                   <span class="font-weight-stone-600 text-default">en vedette</span> 
                   attirent 5 fois plus de visiteurs.'
            ];
        } elseif ($type == 5) {
            return [
                'name' => 'Annonce encadrée',
                'url' => 'images/option/5.png',
                'description' => 'Fait ressortir votre annonce en bleu pour qu’elle soit 
                vue davantage et qu’elle obtienne plus de réponses.'
            ];
        } else {
            return [
                'name' => '',
                'url' => 'images/option/5.png',
                'description' => ''
            ];
        }
    }
}


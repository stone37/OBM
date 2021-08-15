<?php

namespace App\Twig;

use App\Entity\Product;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigProductNameExtension extends AbstractExtension
{
    const TYPE_CREDIT = 'credit';
    const TYPE_OV = 'ov';
    const TYPE_OP = 'op';
    const TYPE_PACK = 'pcp';
    const TYPE_V = 'v';

    public function getFunctions()
    {
        return array(new TwigFunction('app_product_name', array($this, 'getName')));
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getName(Product $product)
    {
        if ($product->getCategory() === self::TYPE_CREDIT) {
            $text = "Achat de ".$product->getPrice()." crédits";

            if ($product->getCredit())
                $text .=" + ". $product->getCredit(). " crédits offerts";

        } elseif ($product->getCategory() === self::TYPE_OP) {
            $text = $product->getPhotoPaying()." Photos supplémentaires";
        } elseif ($product->getCategory() === self::TYPE_OV) {
            if ($product->getType() === 1) {
                $text = 'Annonce en tête de liste pendant ' . $product->getDays() . ' jour(s)';
            } elseif ($product->getType() === 2) {
                $text = 'Logo urgent pendant ' . $product->getDays() . ' jour(s)';
            } elseif ($product->getType() === 3) {
                $text = 'Galerie de la page d\'accueil pendant ' . $product->getDays() . ' jour(s)';
            } elseif ($product->getType() === 4) {
                $text = 'Annonce en vedette pendant ' . $product->getDays() . ' jour(s)';
            } else {
                $text = 'Annonce encadrée pendant ' . $product->getDays() . ' jour(s)';
            }
        } elseif ($product->getCategory() === self::TYPE_PACK) {
            $text = 'Abonnement premium pendant ' . $product->getDays() . ' jour(s)';
        } elseif ($product->getCategory() === self::TYPE_V) {
            $text = 'Achat d\'une vignette pendant ' . $product->getDays() . ' jour(s)';
        } else {
            $text = '';
        }

        return $text;
    }
}
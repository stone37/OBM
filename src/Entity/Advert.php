<?php

namespace App\Entity;

use App\Entity\Traits\AdvertAchatVenteTrait;
use App\Entity\Traits\AdvertAutoTrait;
use App\Entity\Traits\AdvertImmobilierTrait;
use App\Entity\Traits\AdvertOptionTrait;
use App\Entity\Traits\AdvertStatusTrait;
use App\Entity\Traits\AdvertTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\AdvertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Advert
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=AdvertRepository::class)
 * @ORM\MappedSuperclass
 */
class Advert
{
    const AUTO = 'auto-moto';
    const IMMOBILIER = 'immobilier';
    const ACHETER = 'acheter-et-vendre';

    use IdTrait;
    use AdvertTrait;
    use AdvertStatusTrait;
    use PositionTrait;
    use TimestampableTrait;
    use AdvertAutoTrait;
    use AdvertImmobilierTrait;
    use AdvertAchatVenteTrait;
    use AdvertOptionTrait;

    public function __construct()
    {
        $this->__constructAdvert();
    }

    public static function validationGroups(self $advert)
    {
        if ($advert->getCategory()->getSlug() === self::AUTO) {
            if ($advert->getSubCategory()->getSlug() === 'voiture-doccasion') {
                return ['Default', 'VO'];
            } elseif($advert->getSubCategory()->getSlug() === 'location-de-voiture') {
                return ['Default', 'LV'];
            } elseif ($advert->getSubDivision()->getSlug() === 'motocross') {
                return ['Default', 'MC'];
            } elseif ($advert->getSubDivision()->getSlug() === 'motos-sport') {
                return ['Default', 'MS'];
            } elseif ($advert->getSubDivision()->getSlug() === 'routieres') {
                return ['Default', 'MR'];
            } elseif ($advert->getSubDivision()->getSlug() === 'jet-ski-scooter-des-mers') {
                return ['Default', 'JSSM'];
            } elseif ($advert->getSubDivision()->getSlug() === 'vedettes-et-bateaux-a-moteur') {
                return ['Default', 'VBM'];
            } elseif ($advert->getSubDivision()->getSlug() === 'camions-lourd') {
                return ['Default', 'CL'];
            }

        } elseif($advert->getCategory()->getSlug() === self::IMMOBILIER) {
            if ($advert->getSubDivision()->getSlug() === 'appartement' ||
                $advert->getSubDivision()->getSlug() === 'appartement-1') {
                return ['Default', 'Appart'];
            } elseif ($advert->getSubDivision()->getSlug() === 'maison' ||
                $advert->getSubDivision()->getSlug() === 'maison-1') {
                return ['Default', 'Maison'];
            } elseif ($advert->getSubDivision()->getSlug() === 'villa' ||
                $advert->getSubDivision()->getSlug() === 'villa-1') {
                return ['Default', 'Villa'];
            } elseif ($advert->getSubDivision()->getSlug() === 'duplex-triplex' ||
                $advert->getSubDivision()->getSlug() === 'duplex-triplex-1') {
                return ['Default', 'Duplex'];
            } elseif ($advert->getSubDivision()->getSlug() === 'terrain' ||
                $advert->getSubDivision()->getSlug() === 'terrain-1') {
                return ['Default', 'Terrain'];
            } elseif ($advert->getSubDivision()->getSlug() === 'colocation') {
                return ['Default', 'Colocation'];
            } elseif ($advert->getSubDivision()->getSlug() === 'studio' ||
                $advert->getSubDivision()->getSlug() === 'studio-1') {
                return ['Default', 'Studio'];
            } elseif ($advert->getSubDivision()->getSlug() === 'studio-americain' ||
                $advert->getSubDivision()->getSlug() === 'studio-americain-1') {
                return ['Default', 'StudioA'];
            } elseif ($advert->getSubDivision()->getSlug() === 'parking-garage') {
                return ['Default', 'Parking'];
            } elseif ($advert->getSubDivision()->getSlug() === 'autre-2') {
                return ['Default', 'Autre'];
            } elseif ($advert->getSubDivision()->getSlug() === 'espaces-commerciaux-et-bureaux' ||
                $advert->getSubDivision()->getSlug() === 'espaces-commerciaux-et-bureaux-1') {
                return ['Default', 'Bureaux'];
            }
        } elseif ($advert->getCategory()->getSlug() === self::ACHETER) {
            if ($advert->getSubDivision()->getSlug() === 'ipad-et-tablettes') {
                return ['Default', 'AchatVente', 'Ipad'];
            } elseif ($advert->getSubDivision()->getSlug() === 'ordinateurs-de-bureau') {
                return ['Default', 'AchatVente', 'Obureau'];
            } elseif ($advert->getSubDivision()->getSlug() === 'portables') {
                return ['Default', 'AchatVente', 'Oportable'];
            }

            return ['Default', 'AchatVente'];
        }

        return ['Default'];
    }
}



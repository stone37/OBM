<?php

namespace App\Normalizer;

use App\Encoder\PathEncoder;
use App\Entity\Advert;
use RuntimeException;

class AdvertPathNormalizer extends Normalizer
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        if ($object instanceof Advert) {

            if ($object->getSubDivision()) {
                $param = [
                    'category_slug' => $object->getCategory()->getSlug(),
                    'sub_category_slug' => $object->getSubCategory()->getSlug(),
                    'sub_division_slug' => $object->getSubDivision()->getSlug(),
                    'city' => $object->getLocation()->getName(),
                    'reference' => $object->getReference(),
                    'slug' => $object->getSlug()
                ];

                $route = [
                    'path' => 'app_advert_show',
                    'params' => $param,
                ];
            } else {
                $param = [
                    'category_slug' => $object->getCategory()->getSlug(),
                    'sub_category_slug' => $object->getSubCategory()->getSlug(),
                    'city' => $object->getLocation()->getName(),
                    'reference' => $object->getReference(),
                    'slug' => $object->getSlug()
                ];

                $route = [
                    'path' => 'app_advert_show_s',
                    'params' => $param,
                ];
            }

            return $route;
        }
        throw new RuntimeException("Can't normalize path");
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return ($data instanceof Advert)
            && PathEncoder::FORMAT === $format;
    }
}

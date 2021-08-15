<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Zone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LocationController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param SessionInterface $session
     * @param $lng
     * @param $lat
     * @return JsonResponse
     */
    public function latlng(SessionInterface $session, $lng, $lat)
    {
        $session->set('app_location_lng', $lng);
        $session->set('app_location_lat', $lat);

        return new JsonResponse(true);
    }

    /**
     * @param SessionInterface $session
     * @param $name
     * @param $type
     * @return JsonResponse
     */
    public function locationSession(SessionInterface $session, $name, $type)
    {
        if ($type) {
            $session->set('app_user_search_city', $name);
        } else {
            $session->set('app_user_search_zone', $name);
        }

        return new JsonResponse(true);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $city
     * @return JsonResponse
     */
    public function zone(Request $request, EntityManagerInterface $em, $city)
    {
        if (!$request->isXmlHttpRequest()) $this->createNotFoundException('Resource introuvable');
        $zones = $em->getRepository(Zone::class)->getZone($city, $request->request->get('q'));

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = $serializer->serialize($zones, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => []]);

        return new JsonResponse($response);
    }
}

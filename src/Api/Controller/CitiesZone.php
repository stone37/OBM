<?php

namespace App\Api\Controller;

use App\Entity\City;
use App\Entity\Zone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CitiesZone extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param City $city
     */
    public function __invoke(Request $request, City $city, $data)
    {
        $zone = $this->em->getRepository(Zone::class)
            ->getZone($city->getName(), $request->query->get('q'));

        return $zone;
    }
}



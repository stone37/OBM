<?php

namespace App\Manager;

use App\Entity\Pub;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class PubManager
{
    private $em;

    public  function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getHome($type)
    {
        return $this->em->getRepository(Pub::class)->getHome($type);
    }

    public function getListing(Request $request, $type)
    {
        $category_slug     = $request->attributes->get('category_slug');
        $sub_category_slug = $request->attributes->get('sub_category_slug');
        $sub_division_slug = $request->attributes->get('sub_division_slug');

        if ($sub_division_slug)
            $category = $sub_division_slug;
        elseif ($sub_category_slug)
            $category = $sub_category_slug;
        else
            $category = $category_slug;

        return $this->em->getRepository(Pub::class)->getListing($category,  $type);
    }
}


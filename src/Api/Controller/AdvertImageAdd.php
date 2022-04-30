<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\AdvertPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertImageAdd extends AbstractController 
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Advert $advert, Request $request)
    {
       $files = $request->files;

        foreach ($files as $key => $file) {
            $image = (new AdvertPicture())
                ->setFile($file)
                ->setPrincipale(($key == 'file') ? true : false );

            $this->em->persist($image);
            $advert->addImage($image);
        }

        $this->em->flush();

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Upload successfully'], Response::HTTP_OK);
    }
}



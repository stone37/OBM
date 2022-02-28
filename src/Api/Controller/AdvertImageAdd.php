<?php

namespace App\Api\Controller;

use App\Service\ApiOrphanageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertImageAdd extends AbstractController 
{
    private ApiOrphanageManager $orphanageManager;

    public function __construct(ApiOrphanageManager $orphanageManager)
    {
        $this->orphanageManager = $orphanageManager;
    }
 
    public function __invoke(Request $request)
    {
        $files = $request->files;
 
        foreach ($files as $file) {
            try {
                try {
                    $this->orphanageManager->upload($file);
                } catch (FileException $e) {}
            } catch (UploadException $e) {}
        }

        return $this->json(['code' => Response::HTTP_OK, 'message' => 'Upload successfully'], Response::HTTP_OK);
    }
}



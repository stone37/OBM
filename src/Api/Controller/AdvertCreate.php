<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\AdvertPicture;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Service\UniqueSuiteNumberGenerator;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AdvertCreate extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private UniqueSuiteNumberGenerator $numberGenerator;
    private UploadService $upload;
    private SessionInterface $session;

    public function __construct(
        CategoryRepository $categoryRepository,
        UniqueSuiteNumberGenerator $numberGenerator,
        UploadService $uploadService,
        SessionInterface $session)
    {
        $this->categoryRepository = $categoryRepository;
        $this->numberGenerator = $numberGenerator;
        $this->upload = $uploadService;
        $this->session = $session;
    }


    /**
     * @param Advert $data
     * @param Request $request
     */
    public function __invoke($data, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $data->setCategory($this->categoryRepository->find((int)$request->query->get('category')));
        $data->setSubCategory($this->categoryRepository->find((int)$request->query->get('subCategory')));
        $data->setSubDivision($this->categoryRepository->find((int)$request->query->get('subDivision')));
        $data->setUser($user);
        $data->setReference($this->numberGenerator->generate(9));

        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $files = $this->upload->getFilesUpload($this->session);

        $sessionArray = $this->session->get('app_advert_image');
        $principale = [];

        foreach ($sessionArray as $values) {
            foreach ($values as $key => $value) {
                $principale[$key] = $value;
            }
        }

        foreach ($files as $file) {
            $image = (new AdvertPicture())
                ->setFile(new File($file->getPathname()))
                ->setPrincipale((bool)$principale[$file->getFilename()]);

            $data->addImage($image);
        }

        return $data;
    }
}



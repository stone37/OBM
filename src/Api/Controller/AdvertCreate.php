<?php

namespace App\Api\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\User;
use App\Service\UniqueSuiteNumberGenerator;
use App\Service\UploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class AdvertCreate extends AbstractController
{
    private UniqueSuiteNumberGenerator $numberGenerator;

    public function __construct(
        UniqueSuiteNumberGenerator $numberGenerator,
        UploadService $uploadService,
        SessionInterface $session)
    {
        $this->numberGenerator = $numberGenerator;
        $this->upload = $uploadService;
        $this->session = $session; 
    }

    /**
     * @param Advert $data
     * @param Category $category
     */
    public function __invoke(Category $category, $data)
    {
        /** @var User $user */
        $user = $this->getUser();

        $data = $this->addCategory($data, $category);

        $data->setUser($user); 
        $data->setReference($this->numberGenerator->generate(9));

        return $data;
    }

    private function addCategory(Advert $advert, Category $category): Advert
    {
        if ($category->getLevelDepth() == 1) {
            $advert->setCategory($category->getParent());
            $advert->setSubCategory($category);
        } else {
            $advert->setCategory($category->getParent()->getParent());
            $advert->setSubCategory($category->getParent());
            $advert->setSubDivision($category);
        }
        
        return $advert;
    }
}



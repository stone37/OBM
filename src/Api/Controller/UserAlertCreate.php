<?php

namespace App\Api\Controller;

use App\Entity\Alert;
use App\Entity\Category;
use App\Manager\AlertManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserAlertCreate extends AbstractController
{
    private AlertManager $manager;

    public function __construct(EntityManagerInterface $em, AlertManager $manager)
    {
        $this->em = $em;
        $this->manager = $manager;
    }

    /**
     * @param Category $category
     * @param Alert $data
     */
    public function __invoke(Category $category, $data)
    {
        if ($category->getLevelDepth() == 0) {
            if ($this->manager->hasAlert($category)) {
                return $this->json(
                    ['code' => Response::HTTP_OK, 'message' => 'Vous avez deja crée une alerte de ce type'],
                    Response::HTTP_OK
                );
            }

            $data = $this->manager->createApiAlert($data, $category);

        } elseif ($category->getLevelDepth() == 1) {
            if ($this->manager->hasAlert($category->getParent(), $category)) {
                return $this->json(
                    ['code' => Response::HTTP_OK, 'message' => 'Vous avez deja crée une alerte de ce type'],
                    Response::HTTP_OK
                );
            }

            $data = $this->manager->createApiAlert($data, $category->getParent(), $category);
        } else {
            if ($this->manager->hasAlert($category->getParent()->getParent(), $category->getParent(), $category)) {
                return $this->json(
                    ['code' => Response::HTTP_OK, 'message' => 'Vous avez deja crée une alerte de ce type'],
                    Response::HTTP_OK
                );
            }

            $data = $this->manager->createApiAlert($data, $category->getParent()->getParent(), $category->getParent(), $category);
        }

        return $data;
    }
}



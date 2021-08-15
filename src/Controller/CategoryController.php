<?php

namespace App\Controller;

use App\Controller\Traits\ControllerTrait;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategoryController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param EntityManagerInterface $em
     * @param $id
     * @return JsonResponse
     */
    public function byParent(EntityManagerInterface $em, $id)
    {
        $categories = $em->getRepository(Category::class)->getWithParent($id);

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = $serializer->serialize($categories, 'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES =>
                ['createdAt', 'updatedAt', 'rootNode',
                    'leftNode', 'rightNode', 'levelDepth', 'permalink', 'childrenNumber']]);

        return new JsonResponse($response);
    }

    /**
     * @param EntityManagerInterface $em
     * @param $slug
     * @return JsonResponse
     */
    public function byParentSlug(EntityManagerInterface $em, $slug)
    {
        $categories = $em->getRepository(Category::class)->getWithParentSlug($slug);

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $serializer = new Serializer([$normalizer], [$encoder]);
        $response = $serializer->serialize($categories, 'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES =>
                ['createdAt', 'updatedAt', 'rootNode',
                    'leftNode', 'rightNode', 'levelDepth', 'permalink', 'childrenNumber']]);

        return new JsonResponse($response);
    }

}

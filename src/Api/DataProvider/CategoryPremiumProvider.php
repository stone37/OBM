<?php

namespace App\Api\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\RuntimeException;
use App\Api\Resource\CategoryPremiumResource;
use App\Api\Resource\CategoryResource;
use App\Entity\CategoryPremium;
use App\Repository\CategoryPremiumRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CategoryPremiumProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private RequestStack $requestStack;
    private CategoryPremiumRepository $categoryRepository;

    private UploaderHelper $uploaderHelper;

    public function __construct(
        RequestStack $requestStack,
        CategoryPremiumRepository $categoryRepository,
        UploaderHelper $uploaderHelper
    ) {
        $this->requestStack = $requestStack;
        $this->categoryRepository = $categoryRepository;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return CategoryPremiumResource::class === $resourceClass;
    }

    /**
     * @return array<CategoryResource>
     */
    public function getCollection(string $resourceClass, string $operationName = null): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new RuntimeException('Requête introuvable');
        }

        return array_map(
            fn (CategoryPremium $category) => CategoryPremiumResource::fromCategory($category, $this->uploaderHelper),
            $this->categoryRepository->findForApi()
        );
    }
}

<?php

namespace App\Api\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\RuntimeException;
use App\Api\Resource\CategoryResource;
use App\Entity\Category;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class CategoryProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    private RequestStack $requestStack;
    private CategoryRepository $categoryRepository;

    private UploaderHelper $uploaderHelper;

    private AdvertRepository $repository;

    public function __construct(
        RequestStack $requestStack,
        CategoryRepository $categoryRepository,
        UploaderHelper $uploaderHelper,
        AdvertRepository $repository
    ) {
        $this->requestStack = $requestStack;
        $this->categoryRepository = $categoryRepository;
        $this->uploaderHelper = $uploaderHelper;
        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return CategoryResource::class === $resourceClass;
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
            fn (Category $category) => CategoryResource::fromCategory($category, $this->repository, $this->uploaderHelper),
            $this->categoryRepository->findForApi()
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param int|array $id
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?CategoryResource
    {
        if (is_array($id)) {
            throw new RuntimeException('id as array not expected');
        }

        $category = $this->categoryRepository->findPartial((int) $id);

        return $category ? categoryResource::fromCategory($category, $this->repository, $this->uploaderHelper) : null;
    }
}

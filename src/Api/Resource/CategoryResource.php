<?php

namespace App\Api\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Category;
use App\Repository\AdvertRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * @ApiResource(
 *     shortName="Category",
 *     normalizationContext={"groups"={"read:category"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     paginationEnabled=false
 * )
 */
class CategoryResource
{
    /**
     * @Groups({"read:category"})
     * @ApiProperty(identifier=true)
     */
    public ?int $id = null;

    /**
     * @Groups({"read:category"})
     * @Assert\NotBlank(groups={"anonymous"}, normalizer="trim")
     */
    public ?string $name = null;

    /**
     * @Groups({"read:category"})
     */
    public ?string $slug = null;

    /**
     * @Groups({"read:category"})
     */
    public ?string $icon = null;

    /**
     * @Groups({"read:category"})
     */
    public ?string $image = null;

    /**
     * @Groups({"read:category"})
     */
    public ?int $parent = null;

    /**
     * @Groups({"read:category"})
     */
    public ?array $children = [];

    /**
     * @Groups({"read:category"})
     */
    public ?int $advert = 0;

    /**
     * @Groups({"read:category"})
     */
    public ?int $levelDepth = null;

    public static function fromCategory(Category $category, AdvertRepository $repository, ?UploaderHelper $uploaderHelper = null): CategoryResource
    {
        $resource = new self();
        $resource->id = $category->getId();
        $resource->name = $category->getName();
        $resource->slug = $category->getSlug();
        $resource->icon = $category->getIcon();
        $resource->levelDepth = $category->getLevelDepth();
        $resource->parent = null !== $category->getParent() ? $category->getParent()->getId() : 0;
        $resource->advert = $repository->apiCountByCategory($category);

        if ($category && $uploaderHelper && $category->getFileName()) {
            $resource->image = $uploaderHelper->asset($category, 'file');
        }

        if ($category->getChildren()) {

            /** @var Category $children */
            foreach ($category->getChildren() as $children) {
                $data['id'] = $children->getId();
                $data['name'] = $children->getName();
                $data['slug'] = $children->getSlug();
                $data['icon'] = $children->getIcon();
                $data['levelDepth'] = $children->getLevelDepth();
                $data['parent'] = null !== $children->getParent() ? $children->getParent()->getId() : 0;
                $data['advert'] = (int) $repository->apiCountByCategory($children);
                $data['children'] = count($children->getChildren());

                if ($uploaderHelper && $children->getFileName()) {
                    $data['image'] = $uploaderHelper->asset($children, 'file');
                }

                /*if ($children->getChildren()) {
                    foreach ($children->getChildren() as $child) {
                        $subs['id'] = $child->getId();
                        $subs['name'] = $child->getName();
                        $subs['slug'] = $child->getSlug();
                        $subs['icon'] = $child->getIcon();
                        $subs['parent'] = null !== $child->getParent() ? $child->getParent()->getId() : 0;

                        $data['children'][] = $subs;
                        $subs = [];

                        if ($uploaderHelper && $child->getFileName()) {
                            $subs['image'] = $uploaderHelper->asset($child, 'file');
                        } else {
                            $resource->image = '/images/default.png';
                        }
                    }
                }*/

                $resource->children[] = $data;
                $data = [];
            }
        }

        return $resource;
    }
}

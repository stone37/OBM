<?php

namespace App\Api\Resource;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Category;
use App\Entity\CategoryPremium;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use ApiPlatform\Core\Action\NotFoundAction;

/**
 * @ApiResource(
 *     shortName="Category/Premium",
 *     normalizationContext={"groups"={"read:premium"}},
 *     collectionOperations={
 *          "get"={"path"="/categories/premiums"}
 *     },
 *     itemOperations={
 *         "get"={
 *             "controller"=NotFoundAction::class,
 *             "openapi_context"={
                    "summary"="hidden"
 *             },
 *             "read"=false,
 *             "output"=false,
 *         },
 *     },
 *     paginationEnabled=false
 * )
 */
class CategoryPremiumResource
{
    /**
     * @Groups({"read:premium"})
     * @ApiProperty(identifier=true)
     */
    public ?int $id = null;

    /**
     * @Groups({"read:premium"})
     * @Assert\NotBlank(groups={"anonymous"}, normalizer="trim")
     */
    public ?string $name = null;

    /**
     * @Groups({"read:premium"})
     */
    public ?array $categories = [];

    public static function fromCategory(CategoryPremium $category, ?UploaderHelper $uploaderHelper = null): CategoryPremiumResource
    {
        $resource = new self();
        $resource->id = $category->getId();
        $resource->name = $category->getName();

        if ($category->getCategories()) {

            /** @var Category $children */
            foreach ($category->getCategories() as $children) {
                $data['id'] = $children->getId();
                $data['name'] = $children->getName();
                $data['slug'] = $children->getSlug();
                $data['icon'] = $children->getIcon();
                $data['levelDepth'] = $children->getLevelDepth();
                //$data['parent'] = null !== $children->getParent() ? $children->getParent()->getId() : 0;

                if ($children->getLevelDepth() == 1) {
                    $data['parent'] = [
                        'id' => $children->getParent()->getId(),
                        'slug' => $children->getParent()->getSlug()
                    ];
                } else if ($children->getLevelDepth() == 2) {
                    $data['parent'] = [
                        'id' => $children->getParent()->getParent()->getId(),
                        'slug' => $children->getParent()->getParent()->getSlug()
                    ];
                } else {
                    $data['parent_data'] = '';
                }

                if ($uploaderHelper && $children->getFileName()) {
                    $data['image'] = $uploaderHelper->asset($children, 'file');
                } else {
                    $data['image'] = '';
                }

                $resource->categories[] = $data;
            }
        }

        return $resource;
    }
}

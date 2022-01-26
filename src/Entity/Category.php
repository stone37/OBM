<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\CategoryTreeTrait;
use App\Entity\Traits\EnabledTrait;
use App\Entity\Traits\MediaTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\CategoryTrait;
use Serializable;
use ApiPlatform\Core\Action\NotFoundAction;

/**
 * Class Category
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @Vich\Uploadable
 * @Gedmo\Tree(type="nested")
 * @ORM\MappedSuperclass
 *
 * ApiResource()
 */
class Category implements Serializable
{
    use IdTrait;
    use PositionTrait;
    use EnabledTrait;
    use TimestampableTrait;
    use CategoryTreeTrait;
    use CategoryTrait;
    use MediaTrait;

    public function __construct()
    {
        $this->__constructCategory();

        $this->enabled = true;
    }
}

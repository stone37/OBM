<?php

namespace App\Event;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class AdvertListEvent
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Category|null
     */
    private $category;

    public function  __construct(Request $request, Category $category = null)
    {
        $this->request = $request;
        $this->category = $category;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}


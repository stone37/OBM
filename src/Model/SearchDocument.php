<?php

namespace App\Model;

/**
 * Représente un document indexable par le système de recherche.
 */
class SearchDocument
{
    public $title;

    public $content;

    /**
     * @var string[]
     */
    public $category;

    /**
     * @var string[]
     */
    public $subCategory;

    /**
     * @var string[]
     */
    public $subDivisions;

    public $type;

    public $created_at;

    public $location_name;
}

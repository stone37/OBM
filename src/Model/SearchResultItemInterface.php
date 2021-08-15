<?php

namespace App\Model;

use DateTimeInterface;

interface SearchResultItemInterface
{
    public function getTitle(): string;

    public function getExcerpt(): string;

    public function getType(): string;

    public function getUrl(): string;

    public function getCreatedAt(): DateTimeInterface;

    public function getCategories(): array;

    //public function getSubCategories(): array;

    //public function getSubDivisions(): array;
}

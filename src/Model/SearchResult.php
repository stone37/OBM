<?php

namespace App\Model;

class SearchResult
{
    private $items;
    private $total;

    /**
     * @param SearchResultItemInterface[] $results
     */
    public function __construct(array $results, int $total)
    {
        $this->items = $results;
        $this->total = $total;
    }

    /**
     * @return SearchResultItemInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}

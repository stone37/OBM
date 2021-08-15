<?php

namespace App\Model\Typesense;

use App\Model\SearchInterface;
use App\Model\SearchResult;
use GuzzleHttp\Psr7\Query;

class TypesenseSearch implements SearchInterface
{
    private $client;

    public function __construct(TypesenseClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $q
     * @param array $types
     * @param int $limit
     * @param int $page
     * @return SearchResult
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function search(string $q, array $types = [], int $limit = 50, int $page = 1): SearchResult
    {
        $query = [
            'q' => $q,
            'page' => $page,
            'query_by' => 'title,category,subCategory,subDivision,content',
            'highlight_full_fields' => 'content,title',
            'highlight_affix_num_tokens' => 4,
            'per_page' => $limit,
            'num_typos' => 1,
        ];

        if (!empty($types)) {
            $query['filter_by'] = 'type:['.implode(',', $types).']';
        }

        ['found' => $found, 'hits' => $items] = $this->client->get('collections/content/documents/search?'.Query::build($query));

        return new SearchResult(array_map(function (array $item) {return new TypesenseItem($item);}, $items), $found > 10 * $limit ? 10 * $limit : $found);
    }
}

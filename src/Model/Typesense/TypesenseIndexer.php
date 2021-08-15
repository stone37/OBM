<?php

namespace App\Model\Typesense;

use App\Exception\TypesenseException;
use App\Model\IndexerInterface;
use Symfony\Component\HttpFoundation\Response;

class TypesenseIndexer implements IndexerInterface
{
    private $client;

    public function __construct(TypesenseClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $data
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(array $data): void
    {
        try {
            $this->client->patch("collections/content/documents/{$data['id']}", $data);
        } catch (TypesenseException $exception) {
            if (Response::HTTP_NOT_FOUND === $exception->status && 'Not Found' === $exception->message) {
                $this->client->post('collections', [
                    'name' => 'content',
                    'fields' => [
                        ['name' => 'title', 'type' => 'string'],
                        ['name' => 'content', 'type' => 'string'],
                        ['name' => 'category', 'type' => 'string[]'],
                        ['name' => 'subCategory', 'type' => 'string[]'],
                        ['name' => 'subDivision', 'type' => 'string[]'],
                        ['name' => 'location', 'type' => 'string', 'facet' => true],
                        ['name' => 'location', 'type' => 'string', 'facet' => true],
                        ['name' => 'type', 'type' => 'string', 'facet' => true],
                        ['name' => 'created_at', 'type' => 'int32'],
                        //['name' => 'validated_at', 'type' => 'int32'],
                        ['name' => 'url', 'type' => 'string'],
                        //['name' => 'traitement', 'type' => 'string', 'facet' => true],
                    ],
                    'default_sorting_field' => 'validated_at',
                ]);
                $this->client->post('collections/content/documents', $data);
            } elseif (Response::HTTP_NOT_FOUND === $exception->status) {
                $this->client->post('collections/content/documents', $data);
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param string $id
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function remove(string $id): void
    {
        $this->client->delete("collections/content/documents/$id");
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function clean(): void
    {
        try {
            $this->client->delete('collections/content');
        } catch (TypesenseException $e) {
        }
    }
}

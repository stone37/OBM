<?php

namespace App\Model\Typesense;

use RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Exception\TypesenseException;

class TypesenseClient
{
    private $host;
    private $apiKey;
    private $client;

    public function __construct(string $host, string $apiKey, HttpClientInterface $client)
    {
        if (empty($apiKey)) {
            throw new RuntimeException("Une clef d'API est nécessaire à l'utilisation de typesense");
        }
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * @param string $endpoint
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(string $endpoint): array
    {
        return $this->api($endpoint, [], 'GET');
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->api($endpoint, $data, 'POST');
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function patch(string $endpoint, array $data = []): array
    {
        return $this->api($endpoint, $data, 'PATCH');
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function delete(string $endpoint, array $data = []): array
    {
        return $this->api($endpoint, $data, 'DELETE');
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function api(string $endpoint, array $data = [], string $method = 'POST'): array
    {
        $response = $this->client->request($method, "http://{$this->host}/{$endpoint}", [
            'json' => $data,
            'headers' => [
                'X-TYPESENSE-API-KEY' => $this->apiKey,
            ],
        ]);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode($response->getContent(), true);
        }

        throw new TypesenseException($response);
    }
}

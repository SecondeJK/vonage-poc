<?php

namespace App\Application\Services;

use GuzzleHttp\Client;

class VonageApiService {

    protected string $apiKey;

    protected string $apiSecret;

    protected string $baseUrl;

    protected Client $httpClient;

    public function __construct(
        string $apiKey,
        string $apiSecret,
        string $baseUrl,
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;
        $this->setupBaseUrl();
    }

    private function returnAuthQueryString(): string
    {
        $payload = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
        ];

        return http_build_query($payload);
    }

    private function setupBaseUrl(): void
    {
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
        ]);
    }

    private function get(string $url)
    {
        return $this->httpClient->get($url);
    }

    private function post(string $url)
    {
        return $this->httpClient->post($url);
    }

    private function patch(string $url)
    {
        return $this->httpClient->patch($url);
    }

    public function getAccountBalance()
    {
        $response= $this->get('account/get-balance?'. $this->returnAuthQueryString());
        $returnedPayload = json_decode($response->getBody()->getContents(), true);

        return $returnedPayload['value'];
    }
}   
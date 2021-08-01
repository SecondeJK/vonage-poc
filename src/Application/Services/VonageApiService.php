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
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->buildClient();
    }

    private function returnAuthQueryString(): string
    {
        $payload = [
            'api_key' => $this->apiKey,
            'api_secret' => $this->apiSecret,
        ];

        return http_build_query($payload);
    }

    private function setBase64Auth(): void
    {

    }

    private function buildClient(): void
    {
        $this->httpClient = new Client();
    }

    private function useBaseUrl(string $apiIdentifier): void
    {
        switch ($apiIdentifier) {
            case 'restNexmo':
                $baseUrl = 'https://rest.nexmo.com';
            case 'v2Nexmo':
                $baseUrl = 'https://api.nexmo.com/v2';
        }

        $this->httpClient->setDefaultOption('base_uri', $baseUrl);
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

    public function getAccountBalance(): int
    {
        $this->useBaseUrl(apiIdentifier: 'restNexmo');

        $response= $this->get('account/get-balance?'. $this->returnAuthQueryString());
        $returnedPayload = json_decode($response->getBody()->getContents(), true);

        return (int)$returnedPayload['value'];
    }
}   
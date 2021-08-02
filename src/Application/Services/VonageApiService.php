<?php

namespace App\Application\Services;

use GuzzleHttp\Client;

class VonageApiService {

    protected string $apiKey;

    protected string $apiSecret;

    /**
     * baseURL has to be calculated here,
     * cannot use DI because baseURL on guzzle cannot be
     * dynamically reclared and this task uses two totally
     * different API endpoints.
     */
    protected array $baseUrl = [
        'account' => 'https://rest.nexmo.com/',
        'v2' => 'https://api.nexmo.com/v2/'
    ];

    protected Client $httpClient;

    public function __construct(
        string $apiKey,
        string $apiSecret,
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->httpClient = new Client();
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
        $response= $this->get($this->baseUrl['account'] . 'account/get-balance?'. $this->returnAuthQueryString());
        $returnedPayload = json_decode($response->getBody()->getContents(), true);

        return $returnedPayload['value'];
    }

    public function getApplications(): array
    {
        return ['ExampleApp1', 'ExampleApp2'];
    }
}   
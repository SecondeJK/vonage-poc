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

    private function base64Auth(): string
    {
        return 'Basic ' . base64_encode($this->apiKey . ':' . $this->apiSecret);
    }

    public function getAccountBalance()
    {
        $response = $this->httpClient->get($this->baseUrl['account'] . 'account/get-balance?'. $this->returnAuthQueryString());
        $returnedPayload = json_decode($response->getBody()->getContents(), true);

        return $returnedPayload['value'];
    }

    public function getApplications(): array
    {
        $response = $this->httpClient->get($this->baseUrl['v2'] . 'applications', [
            'headers' => [
                'Authorization' => $this->base64Auth(),
            ],
        ]);

        $responsePayload = json_decode($response->getBody()->getContents(), true);
        return $responsePayload['_embedded']['applications'];
    }
}   
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
        'v2' => 'https://api.nexmo.com/v2/',
        'sms' => 'https://api.nexmo.com/v0.1/',
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

    public function createVonageApplication($payload)
    {
        $formattedPayload = [
            'name' => $payload['application_name'],
        ];

        $response = $this->httpClient->post($this->baseUrl['v2'] . 'applications', [
            'headers' => [
                'Authorization' => $this->base64Auth(),
                'Content-Type' => 'application/json',
            ],
            'json' => $formattedPayload,
        ]);

        if (!$response->getStatusCode(200)) {
            return false;
        }

        return true;
    }

    public function updateVonageApplication($payload)
    {
        $formattedPayload = [
            'name' => $payload['application_name'],
        ];

        $response = $this->httpClient->put($this->baseUrl['v2'] . 'applications/' . $payload['application_id'], [
            'headers' => [
                'Authorization' => $this->base64Auth(),
                'Content-Type' => 'application/json',
            ],
            'json' => $formattedPayload,
        ]);
    }

    public function deleteVonageApplication($payload)
    {
        $response = $this->httpClient->delete($this->baseUrl['v2'] . 'applications/' . $payload['application_id'], [
            'headers' => [
                'Authorization' => $this->base64Auth(),
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function sendSms($payload)
    {
        $formattedPayload = [
            'from' => [
                'type' => 'sms',
                'number' => $payload['sms_from']
            ],
            'to' => [
                'type' => 'sms',
                'number' => $payload['sms_to']
            ],
            'message' => [
                'content' => [
                    'type' => 'text',
                    'text' => $payload['sms_message']
                ]
            ],
        ];

        $response = $this->httpClient->post($this->baseUrl['sms'] . 'messages/', [
            'headers' => [
                'Authorization' => $this->base64Auth(),
                'Content-Type' => 'application/json',
            ],
            'json' => $formattedPayload,
        ]);
    }
}   
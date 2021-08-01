<?php

namespace App\Application\Services;

class VonageApiService {

    public string $apiKey;

    public string $apiSecret;

    public string $baseUrl;

    public function __construct(
        string $apiKey,
        string $apiSecret,
        string $baseUrl
    ) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;
    }

    private function get(string $url)
    {

    }

    public function getBalance()
    {
        
    }
}   
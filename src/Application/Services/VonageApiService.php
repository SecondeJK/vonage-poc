<?php

namespace App\Application\Services;

class VonageApiService {

    public string $apiKey;

    public string $apiSecret;

    public function __construct($apiKey, $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }
}   
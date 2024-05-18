<?php

namespace App\Services\ApiService\DTO;

use App\Services\ApiService\Enums\HttpRequestMethod;

class ApiRequestData
{
    public function __construct(
        public readonly HttpRequestMethod $method,
        public readonly string $url,
        public readonly array $payload = [],
        public readonly array $queryParams = [],
        public readonly array $headers = []
    ) {
    }
}

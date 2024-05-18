<?php

namespace App\Services\ApiService;

use App\Services\ApiService\DTO\ApiRequestData;

interface ApiServiceInterface
{
    public function makeRequest(ApiRequestData $dto);
}

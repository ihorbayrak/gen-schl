<?php

namespace App\Services\RateService\Providers;

use App\Services\ApiService\ApiServiceInterface;
use App\Services\ApiService\DTO\ApiRequestData;
use App\Services\ApiService\Enums\HttpRequestMethod;
use App\Services\RateService\CurrencyRate;
use App\Services\RateService\Enums\CurrencyCode;

class NbuRateProvider implements RateProviderInterface
{
    private string $baseUrl = 'bank.gov.ua/NBUStatService/v1/statdirectory/exchange';

    public function __construct(private readonly ApiServiceInterface $apiService)
    {
    }

    public function getCurrent(CurrencyCode $currencyCode): CurrencyRate
    {
        $dto = new ApiRequestData(
            method: HttpRequestMethod::GET,
            url: $this->baseUrl,
            queryParams: [
                'valcode' => $currencyCode->value,
                'json'
            ]
        );

        $rate = $this->apiService->makeRequest($dto)[0]['rate'];

        return new CurrencyRate($rate);
    }
}

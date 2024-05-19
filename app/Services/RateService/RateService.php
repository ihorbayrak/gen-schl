<?php

namespace App\Services\RateService;

use App\Services\RateService\Enums\CurrencyCode;
use App\Services\RateService\Providers\RateProviderInterface;
use Illuminate\Support\Facades\Cache;

class RateService
{
    public const CACHE_KEY = 'rate';
    public const CACHE_TTL = 60; // 1 minute

    public function __construct(private readonly RateProviderInterface $rateProvider)
    {
    }

    public function getCurrentRate(): float
    {
        // I want to reduce api calls, so I put rate value in cache for 1 minute
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            $currencyRate = $this->rateProvider->getCurrent(CurrencyCode::USD);

            return $currencyRate->getRate();
        });
    }
}

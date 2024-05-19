<?php

namespace Tests\Unit\Services\Rate;

use App\Services\RateService\CurrencyRate;
use App\Services\RateService\Enums\CurrencyCode;
use App\Services\RateService\Providers\RateProviderInterface;
use App\Services\RateService\RateService;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Mockery;

class RateServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetCurrentRateReturnsCachedValue()
    {
        $rateValueMock = 8.4272;
        $rateProviderMock = Mockery::mock(RateProviderInterface::class);
        $rateService = new RateService($rateProviderMock);

        Cache::shouldReceive('remember')
            ->once()
            ->with(RateService::CACHE_KEY, RateService::CACHE_TTL, Mockery::type('Closure'))
            ->andReturn($rateValueMock);

        $rate = $rateService->getCurrentRate();

        $this->assertEquals($rateValueMock, $rate);
    }

    public function testGetCurrentRateTakeValueFromRateProviderWhenCacheIsEmpty()
    {
        $rateValueMock = 8.4272;
        $currencyRateMock = Mockery::mock(CurrencyRate::class);
        $currencyRateMock->shouldReceive('getRate')
            ->once()
            ->andReturn($rateValueMock);

        $rateProviderMock = Mockery::mock(RateProviderInterface::class);
        $rateProviderMock->shouldReceive('getCurrent')
            ->once()
            ->with(Mockery::type(CurrencyCode::class))
            ->andReturn($currencyRateMock);

        $rateService = new RateService($rateProviderMock);

        Cache::flush();

        Cache::shouldReceive('remember')
            ->once()
            ->with(RateService::CACHE_KEY, RateService::CACHE_TTL, Mockery::type('Closure'))
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $rate = $rateService->getCurrentRate();

        $this->assertEquals($rateValueMock, $rate);
    }
}

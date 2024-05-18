<?php

namespace App\Providers;

use App\Services\ApiService\ApiServiceInterface;
use App\Services\ApiService\GuzzleService;
use App\Services\RateService\Providers\NbuRateProvider;
use App\Services\RateService\Providers\RateProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApiServiceInterface::class, GuzzleService::class);

        $this->app->bind(RateProviderInterface::class, NbuRateProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

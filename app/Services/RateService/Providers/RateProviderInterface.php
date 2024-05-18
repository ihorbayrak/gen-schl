<?php

namespace App\Services\RateService\Providers;

use App\Services\RateService\CurrencyRate;
use App\Services\RateService\Enums\CurrencyCode;

interface RateProviderInterface
{
    public function getCurrent(CurrencyCode $currencyCode): CurrencyRate;
}

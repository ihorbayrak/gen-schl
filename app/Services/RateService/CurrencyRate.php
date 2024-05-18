<?php

namespace App\Services\RateService;

/**
 * Class that formats rate value
 */
class CurrencyRate
{
    public function __construct(private float $rate)
    {
    }

    public function getRate(): float
    {
        return number_format($this->rate, 4);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\RateService\RateService;

class RateController extends Controller
{
    public function __construct(private RateService $rateService)
    {
    }

    public function __invoke()
    {
        return response()->json($this->rateService->getCurrentRate());
    }
}

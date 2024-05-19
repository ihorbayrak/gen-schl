<?php

use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/rate', RateController::class)->name('rate.current');

Route::post('/subscribe', SubscribeController::class)->name('subscribe');

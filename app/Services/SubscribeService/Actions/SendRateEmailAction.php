<?php

namespace App\Services\SubscribeService\Actions;

use App\Models\User;
use App\Services\SubscribeService\Mail\ExchangeRateEmail;
use Illuminate\Support\Facades\Mail;

class SendRateEmailAction
{
    public function handle(User $user, float $rate)
    {
        Mail::to($user)->send(new ExchangeRateEmail($rate));
    }
}

<?php

namespace App\Services\SubscribeService\Actions;

use App\Models\User;
use App\Services\SubscribeService\Mail\WelcomeSubscribeEmail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailAction
{
    public function handle(User $user)
    {
        Mail::to($user)->send(new WelcomeSubscribeEmail());
    }
}

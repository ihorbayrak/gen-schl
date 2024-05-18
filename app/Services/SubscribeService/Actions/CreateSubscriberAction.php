<?php

namespace App\Services\SubscribeService\Actions;

use App\Models\User;

class CreateSubscriberAction
{
    public function handle(string $email)
    {
        return User::query()->create(['email' => $email]);
    }
}

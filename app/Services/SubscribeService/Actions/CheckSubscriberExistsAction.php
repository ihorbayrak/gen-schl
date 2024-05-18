<?php

namespace App\Services\SubscribeService\Actions;

use App\Models\User;

class CheckSubscriberExistsAction
{
    public function handle(string $email)
    {
        return User::query()->where('email', $email)->first();
    }
}

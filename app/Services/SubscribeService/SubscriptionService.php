<?php

namespace App\Services\SubscribeService;

use App\Models\User;
use App\Services\RateService\RateService;
use App\Services\SubscribeService\Actions\CheckSubscriberExistsAction;
use App\Services\SubscribeService\Actions\CreateSubscriberAction;
use App\Services\SubscribeService\Actions\SendRateEmailAction;
use App\Services\SubscribeService\Actions\SendWelcomeEmailAction;
use App\Services\SubscribeService\Exceptions\SubscriberAlreadyExistsException;

class SubscriptionService
{
    public function __construct(
        private readonly CheckSubscriberExistsAction $checkSubscriberExists,
        private readonly CreateSubscriberAction $createSubscriber,
        private readonly SendWelcomeEmailAction $sendWelcomeEmail,
        private readonly SendRateEmailAction $sendRateEmail,
        private readonly RateService $rateService
    ) {
    }

    /**
     * @throws SubscriberAlreadyExistsException
     */
    public function subscribe(string $email): bool
    {
        if ($this->checkSubscriberExists->handle($email)) {
            throw new SubscriberAlreadyExistsException('Subscriber already exists');
        }

        $subscriber = $this->createSubscriber->handle($email);

        if ($subscriber) {
            $this->sendWelcomeEmail->handle($subscriber);

            return true;
        }
        return false;
    }

    public function sendNewsletter()
    {
        $rate = $this->rateService->getCurrentRate();

        User::query()
            ->cursor()
            ->each(function (User $user) use ($rate) {
                $this->sendRateEmail->handle($user, $rate);
            });
    }
}

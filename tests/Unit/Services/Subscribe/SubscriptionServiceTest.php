<?php

namespace Tests\Unit\Services\Subscribe;

use App\Models\User;
use App\Services\RateService\RateService;
use App\Services\SubscribeService\Exceptions\SubscriberAlreadyExistsException;
use App\Services\SubscribeService\Mail\ExchangeRateEmail;
use App\Services\SubscribeService\Mail\WelcomeSubscribeEmail;
use App\Services\SubscribeService\SubscriptionService;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSubscribeUser()
    {
        Mail::fake();

        $email = 'test@example.com';
        $service = app(SubscriptionService::class);

        $result = $service->subscribe($email);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', ['email' => $email]);

        Mail::assertQueued(WelcomeSubscribeEmail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    public function testSubscribeUserWithExistingEmailThrowsException()
    {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var UserFactory $userFactory */
        $userFactory = User::factory();

        $userData = $userFactory
            ->setEmail($user->email)
            ->definition();

        $service = app(SubscriptionService::class);

        $this->expectException(SubscriberAlreadyExistsException::class);

        $service->subscribe($userData['email']);
    }

    public function testSendsNewsletterToAllSubscribers()
    {
        Mail::fake();

        $rateValueMock = 8.4272;
        $mockRateService = $this->mock(RateService::class);
        $mockRateService->shouldReceive('getCurrentRate')->andReturn($rateValueMock);

        $userCount = 3;
        $users = User::factory($userCount)->create();

        $subscriptionService = app(SubscriptionService::class);

        $subscriptionService->sendNewsletter();

        Mail::assertQueued(ExchangeRateEmail::class, function ($mail) use ($users, $rateValueMock) {
            return $mail->hasTo($users->first()) && $mail->rate === $rateValueMock;
        });

        Mail::assertQueuedCount($userCount);
    }
}

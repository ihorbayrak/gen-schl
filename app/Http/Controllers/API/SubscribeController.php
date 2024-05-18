<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use App\Services\SubscribeService\Exceptions\SubscriberAlreadyExistsException;
use App\Services\SubscribeService\SubscriptionService;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
    }

    public function __invoke(SubscribeRequest $subscribeRequest)
    {
        try {
            $status = $this->subscriptionService->subscribe($subscribeRequest->get('email'));

            if (!$status) {
                return response()->json(['description' => 'Щось пішло не так'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json(['description' => 'Email додано']);
        } catch (SubscriberAlreadyExistsException $e) {
            return response()->json(['description' => 'Цей email вже зайнято'], Response::HTTP_CONFLICT);
        }
    }
}

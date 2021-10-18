<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubscriberRequest;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\Topic;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function createSubscription(CreateSubscriberRequest $request, string $topic): \Illuminate\Http\JsonResponse
    {
        $createTopic = Topic::updateOrCreate(['name' => strtolower($topic)]);
        $subscriber = Subscriber::updateOrCreate(['url' => strtolower($request->url)]);

        $createTopic->subscribers()->syncWithoutDetaching([$subscriber->id]);

        return response()->json([
            'url' => $subscriber->url,
            'topic' => $topic
        ]);
    }
}

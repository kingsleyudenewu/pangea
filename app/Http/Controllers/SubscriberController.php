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
        $createTopic = Topic::firstOrCreate(['name' => strtolower($topic)]);
        $subscriber = Subscriber::firstOrCreate(['url' => strtolower($request->url)]);

        $subscription = Subscription::where('topic_id', $createTopic->id)
            ->where('subscriber_id', $subscriber->id)
            ->exists();

        if (!$subscription) {
            Subscription::create([
                'topic_id' => $createTopic->id,
                'subscriber_id' => $subscriber->id,
            ]);
        }

        return response()->json([
            'url' => $subscriber->url,
            'topic' => $topic
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublishController extends Controller
{
    public function publishMessage(Request $request, string $topic): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'body' => ['required', 'json']
        ]);

        $getTopic = Topic::where('name', strtolower($topic))->first();
        if (empty($getTopic)) {
            abort(404, 'No topic found');
        }

        // Get all subscriptions
        $subscriptions = Subscription::query()->where('topic_id', $getTopic->id)->get();
        $subscriptionUrls = $subscriptions->map(function ($subscription) use ($getTopic, $request) {
            $payload = [
                'topic' => $getTopic->name,
                'data' => $request->body,
            ];
            Http::post($subscription->subscriber->url, $payload);
            return $subscription->subscriber->url;
        });

        return response()->json([
            'topic' => $getTopic->name,
            'data' => $request->toArray(),
        ]);

    }
}

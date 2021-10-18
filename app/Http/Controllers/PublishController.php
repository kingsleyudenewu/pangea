<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublishController extends Controller
{
    public function publishMessage(Request $request, string $topic)
    {
        $request->validate([
            'body' => ['required', 'json']
        ]);

        $getTopic = Topic::where('name', strtolower($topic))->first();
        if (empty($getTopic)) {
            abort(404, 'No topic found');
        }

        // Get all subscriptions
        $subscriptions = $getTopic->subscribers;
        $subscriptions->map(function ($subscription) use ($getTopic, $request) {
            $payload = [
                'topic' => $getTopic->name,
                'data' => $request->body,
            ];
            Http::post($subscription->url, $payload);
            return $subscription->subscriber;
        });

        return response()->json([
            'topic' => $getTopic->name,
            'data' => $request->toArray(),
        ]);

    }
}

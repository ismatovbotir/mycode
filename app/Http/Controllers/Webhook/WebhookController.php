<?php

namespace App\Http\Controllers\Webhook;

use App\Events\WebhookReceived;
use App\Models\Bot;
use App\Models\WebhookEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhookController
{
    public function handle(Request $request, Bot $bot): JsonResponse
    {
        $secret = $request->header('X-Webhook-Secret');

        if (!hash_equals($bot->webhook_secret, $secret ?? '')) {
            return response()->json(['error' => 'Invalid secret'], 403);
        }

        $payload = $request->json()->all();

        $event = WebhookEvent::create([
            'uuid' => Str::uuid(),
            'bot_id' => $bot->id,
            'event_type' => $payload['type'] ?? 'unknown',
            'payload' => $payload,
            'status' => 'pending',
        ]);

        event(new WebhookReceived($event));

        return response()->json(['ok' => true]);
    }
}

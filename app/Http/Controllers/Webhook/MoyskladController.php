<?php
// app/Http/Controllers/Webhook/MoyskladController.php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Jobs\ProcessMoySkladEvent;
use App\Models\Bot;
use App\Models\WebhookEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MoyskladController
{
    public function handle(Request $request, Bot $bot): JsonResponse
    {
        if (!$this->validateSecret($request, $bot)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $payload = $request->json()->all();

        if (!$this->isValidPayload($payload)) {
            return response()->json(['error' => 'Invalid payload structure'], 400);
        }

        try {
            return $this->processWebhook($bot, $payload);
        } catch (\Exception $e) {
            $this->logError($bot, $payload, $e);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    private function validateSecret(Request $request, Bot $bot): bool
    {
        $header = $request->header('X-Webhook-Secret') ?? '';
        return hash_equals($bot->webhook_secret, $header);
    }

    private function isValidPayload(array $payload): bool
    {
        return isset($payload['eventType']) && !empty($payload['eventType']);
    }

    private function processWebhook(Bot $bot, array $payload): JsonResponse
    {
        $eventType = $payload['eventType'];

        // Save webhook event for audit trail
        $event = WebhookEvent::create([
            'bot_id' => $bot->id,
            'event_type' => $eventType,
            'payload' => $payload,
            'status' => 'pending',
        ]);

        // Dispatch job to process the event
        ProcessMoySkladEvent::dispatch($bot, $eventType, $payload)
            ->onQueue('default');

        \Log::info('MoySklad webhook received and queued', [
            'bot_id' => $bot->id,
            'event_type' => $eventType,
            'webhook_event_id' => $event->id,
        ]);

        \Log::channel('webhook')->info('MoySklad webhook processed', [
            'bot_id' => $bot->id,
            'event_type' => $eventType,
        ]);

        return response()->json(['status' => 'received', 'eventType' => $eventType], 200);
    }

    private function logError(Bot $bot, array $payload, \Exception $e): void
    {
        \Log::error('MoySklad webhook processing error', [
            'bot_id' => $bot->id,
            'event_type' => $payload['eventType'] ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}

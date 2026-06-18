<?php
// app/Http/Controllers/Webhook/EntityWebhookController.php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Models\MoySkladWebhook;
use App\Models\UserEntity;
use App\Services\DeveloperNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class EntityWebhookController
{
    public function handle(Request $request, UserEntity $user_entity): JsonResponse
    {
        $notifier = new DeveloperNotificationService();
        $notifier->notifyDevelopment(
            '📡 Webhook Received',
            "user_entity_id: {$user_entity->id}"
        );

        $webhookId = Str::uuid()->toString();
        $payload = $request->all();
        $action = $user_entity->action;
        $entityType = $payload['events'][0]['meta']['type'];

        $bot = $user_entity->user->bot;
        $documentUrl = $payload['events'][0]['meta']['href'];
        $documentId = basename($documentUrl);

        try {

            $webhook = MoySkladWebhook::create([
                'webhook_id' => $webhookId,
                'user_id' => $user_entity->user_id,
                'user_entity_id' => $user_entity->id,
                'bot_id' => $bot->id,
                'event_type' => $action,
                'entity_type' => $entityType,
                'document_url' => $documentUrl,
                'document_id' => $documentId,
                'payload' => $payload,
                'status' => 'received',
            ]);

            $notifier->notifyDevelopment(
                '📡 Webhook Created',
                "webhook_id: {$webhook->id}"
            );



            // Send notification - webhook processed successfully


            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {


            // Send notification - webhook failed
            $notifier->notifyDevelopment(
                '❌ Webhook Failed',
                "user_entity_id: {$user_entity->id}"
            );



            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

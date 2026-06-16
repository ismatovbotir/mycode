<?php
// app/Http/Controllers/Webhook/EntityWebhookController.php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Models\MoySkladWebhook;
use App\Models\UserEntity;
use App\Services\DeveloperNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
        $action = $request->input('action', 'unknown');
        $entityType = $user_entity->entity->type ?? 'unknown';

        try {

            $webhook = MoySkladWebhook::create([
                'webhook_id' => $webhookId,
                'user_entity_id' => $user_entity->id,
                'bot_id' => $user_entity->bot_id,
                'event_type' => strtoupper($action),
                'entity_type' => $entityType,
                'document_url' => $payload['meta']['href'] ?? null,
                'document_id' => $payload['id'] ?? null,
                'payload' => $payload,
                'status' => 'received',
            ]);

            $notifier->notifyDevelopment(
                '📡 Webhook Created',
                "webhook_id: {$webhook->id}"
            );

            // Mark as processing
            $webhook->markProcessing();

            // Process the webhook event
            // This could trigger notifications, data sync, etc.

            // TODO: Add your webhook processing logic here
            // - Fetch document from MoySkład
            // - Extract counterparty phone
            // - Find matching bot_client
            // - Send notification

            // Mark as processed
            //$webhook->markProcessed();



            // Send notification - webhook processed successfully


            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {


            // Send notification - webhook failed
            $notifier->notifyDevelopment(
                '❌ Webhook Failed',
                "user_entity_id: {$e->getMessage()}"
            );



            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

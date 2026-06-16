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
        $webhookId = Str::uuid()->toString();
        $payload = $request->all();
        $action = $request->input('action', 'unknown');
        $entityType = $user_entity->entity->type ?? 'unknown';
        $notifier = new DeveloperNotificationService();

        try {
            Log::channel('webhook')->info('━━ MoySkład Entity Webhook Received ━━', [
                'webhook_id' => $webhookId,
                'user_entity_id' => $user_entity->id,
                'entity_type' => $entityType,
                'action' => $action,
                'bot_id' => $user_entity->bot_id,
            ]);

            // Save webhook to database
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

            Log::channel('webhook')->info('✓ MoySkład webhook saved', [
                'webhook_record_id' => $webhook->id,
                'webhook_id' => $webhookId,
            ]);

            // Send notification - webhook received
            $notifier->notifyWebhookReceived(
                $user_entity->bot->name,
                "{$entityType}:{$action}",
                [
                    'webhook_id' => $webhookId,
                    'document_id' => $payload['id'] ?? null,
                    'document_url' => $payload['meta']['href'] ?? null,
                ]
            );

            // Mark as processing
            $webhook->markProcessing();

            // Process the webhook event
            // This could trigger notifications, data sync, etc.
            Log::channel('webhook')->info('Processing MoySkład webhook...');

            // TODO: Add your webhook processing logic here
            // - Fetch document from MoySkład
            // - Extract counterparty phone
            // - Find matching bot_client
            // - Send notification

            // Mark as processed
            $webhook->markProcessed();

            Log::channel('webhook')->info('━━ MoySkład Webhook Processed ━━', [
                'webhook_id' => $webhookId,
                'status' => 'processed',
            ]);

            // Send notification - webhook processed successfully
            $notifier->notifyDevelopment(
                'MoySkład Webhook ✅',
                "{$user_entity->bot->name} - {$entityType} webhook processed",
                [
                    'webhook_id' => $webhookId,
                    'action' => $action,
                    'record_id' => $webhook->id,
                ]
            );

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::channel('webhook')->error('❌ MoySkład Entity Webhook Error', [
                'webhook_id' => $webhookId,
                'user_entity_id' => $user_entity->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Send notification - webhook failed
            $notifier->notifyWebhookError(
                $user_entity->bot->name,
                "MoySkład webhook failed: " . $e->getMessage(),
                [
                    'webhook_id' => $webhookId,
                    'entity_type' => $entityType,
                    'action' => $action,
                    'user_entity_id' => $user_entity->id,
                ]
            );

            // Mark webhook as failed if it was created
            if (isset($webhook)) {
                $webhook->markFailed($e->getMessage());
            }

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

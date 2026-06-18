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

            // Mark as processing
            $webhook->markProcessing();

            // Process webhook based on entity type
            switch ($entityType) {
                case 'demand':
                    $this->processDemand($webhook, $documentUrl, $documentId);
                    break;
                case 'supply':
                    $this->processSupply($webhook, $documentUrl, $documentId);
                    break;
                case 'invoice':
                    $this->processInvoice($webhook, $documentUrl, $documentId);
                    break;
                case 'paymentin':
                    $this->processPaymentIn($webhook, $documentUrl, $documentId);
                    break;
                case 'paymentout':
                    $this->processPaymentOut($webhook, $documentUrl, $documentId);
                    break;
                case 'salesreturn':
                    $this->processSalesReturn($webhook, $documentUrl, $documentId);
                    break;
            }

            $webhook->markProcessed();



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

    private function processDemand(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch demand from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }

    private function processSupply(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch supply from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }

    private function processInvoice(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch invoice from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }

    private function processPaymentIn(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch incoming payment from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }

    private function processPaymentOut(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch outgoing payment from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }

    private function processSalesReturn(MoySkladWebhook $webhook, string $documentUrl, string $documentId): void
    {
        // TODO: Fetch sales return from MoySkład API
        // TODO: Extract counterparty phone
        // TODO: Find matching bot_client
        // TODO: Send Telegram notification
    }
}

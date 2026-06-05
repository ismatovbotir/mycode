<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendDocumentNotification;
use App\Models\UserEntity;
use App\Models\WebhookEvent;
use App\Services\MoySkladDocumentService;
use App\Services\PhoneMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntityWebhookController extends Controller
{
    public function handle(Request $request, UserEntity $user_entity): mixed
    {
        $payload = $request->all();

        $webhook = $payload['webhook'] ?? [];
        $documentUrl = $webhook['meta']['href'] ?? null;

        Log::channel('webhook')->info('МойСклад webhook received', [
            'user_entity_id' => $user_entity->id,
            'event_type' => $user_entity->entity->type,
            'action' => $user_entity->action,
            'document_url' => $documentUrl,
        ]);

        if (!$documentUrl) {
            return response()->json(['error' => 'Missing document URL'], 400);
        }

        if (!$user_entity->user->moysklad_token) {
            return response()->json(['error' => 'No МойСклад token'], 403);
        }

        $user = $user_entity->user;
        $entity = $user_entity->entity;
        $company = $user->company;

        if (!$entity->is_document) {
            return response()->json(['message' => 'Not a document entity'], 200);
        }

        try {
            $token = decrypt($user->moysklad_token);
            $docService = new MoySkladDocumentService($token);
            $phoneService = new PhoneMatchingService();

            $document = $docService->fetchDocument($documentUrl);

            if (!$document) {
                Log::warning('Failed to fetch document from МойСклад', [
                    'user_entity_id' => $user_entity->id,
                    'url' => $documentUrl,
                ]);

                $webhookEvent = WebhookEvent::create([
                    'user_entity_id' => $user_entity->id,
                    'event_type' => $entity->type,
                    'payload' => $payload,
                    'matched' => false,
                ]);

                return response()->json(['event_id' => $webhookEvent->id], 200);
            }

            $counterpartyPhone = $docService->extractCounterpartyPhone($document);
            $formattedMessage = $docService->formatDocumentForTelegram($document, $entity->document_format);

            $matched = false;
            $botClientId = null;

            if ($counterpartyPhone) {
                $phoneService = new PhoneMatchingService();
                $botClient = $phoneService->findBotClientByPhone($counterpartyPhone, $company->id);

                if ($botClient) {
                    $matched = true;
                    $botClientId = $botClient->id;
                }
            }

            $webhookEvent = WebhookEvent::create([
                'user_entity_id' => $user_entity->id,
                'event_type' => $entity->type,
                'payload' => array_merge($payload, [
                    'formatted_message' => $formattedMessage,
                    'counterparty_phone' => $counterpartyPhone,
                ]),
                'matched' => $matched,
                'bot_client_id' => $botClientId,
                'tg_status' => 'queued',
            ]);

            if ($matched && $botClientId) {
                SendDocumentNotification::dispatch($webhookEvent->id)
                    ->onQueue('telegram')
                    ->delay(now());
            } else {
                Log::info('Document webhook received but no matching client', [
                    'webhook_event_id' => $webhookEvent->id,
                    'counterparty_phone' => $counterpartyPhone,
                ]);
            }

            return response()->json([
                'event_id' => $webhookEvent->id,
                'matched' => $matched,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Exception processing МойСклад webhook', [
                'user_entity_id' => $user_entity->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }
}

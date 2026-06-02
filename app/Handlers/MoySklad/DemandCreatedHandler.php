<?php
// app/Handlers/MoySklad/DemandCreatedHandler.php

declare(strict_types=1);

namespace App\Handlers\MoySklad;

use App\Models\Bot;
use App\Models\WebhookEvent;

class DemandCreatedHandler
{
    public static function handle(Bot $bot, array $payload): void
    {
        $documentId = self::extractDocumentId($payload);

        if (!$documentId) {
            return;
        }

        // Store webhook event for processing
        WebhookEvent::create([
            'bot_id' => $bot->id,
            'event_type' => 'demand',
            'payload' => $payload,
            'status' => 'pending',
        ]);
    }

    private static function extractDocumentId(array $payload): ?string
    {
        return $payload['id'] ?? $payload['documentId'] ?? $payload['entity']['id'] ?? null;
    }
}

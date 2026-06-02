<?php
// app/Handlers/MoySklad/CounterpartyCreatedHandler.php

declare(strict_types=1);

namespace App\Handlers\MoySklad;

use App\Models\Bot;
use App\Models\Client;
use App\Services\MoySkladService;

class CounterpartyCreatedHandler
{
    public static function handle(Bot $bot, array $payload): void
    {
        $counterpartyId = self::extractCounterpartyId($payload);

        if (!$counterpartyId) {
            return;
        }

        // Fetch integration
        $integration = $bot->integrations()
            ->where('type', 'moysklad')
            ->where('is_active', true)
            ->first();

        if (!$integration) {
            return;
        }

        $token = decrypt($integration->credentials['api_token']);
        $service = new MoySkladService($token);

        $counterparty = $service->getCounterpartyById($counterpartyId);

        if (!$counterparty) {
            return;
        }

        self::syncCounterparty($bot->id, $counterparty);
    }

    private static function extractCounterpartyId(array $payload): ?string
    {
        return $payload['id'] ?? $payload['counterpartyId'] ?? $payload['entity']['id'] ?? null;
    }

    private static function syncCounterparty(string $botId, array $counterparty): void
    {
        $phone = null;

        if (!empty($counterparty['phones']) && is_array($counterparty['phones'])) {
            $phone = $counterparty['phones'][0]['number'] ?? null;
        }

        Client::updateOrCreate(
            [
                'bot_id' => $botId,
                'moisklad_id' => $counterparty['id'],
            ],
            [
                'name' => $counterparty['name'] ?? 'Unknown',
                'type' => $counterparty['isUser'] ? 'entity' : 'individual',
                'phone' => $phone,
                'email' => $counterparty['email'] ?? null,
                'inn' => $counterparty['inn'] ?? null,
                'address' => $counterparty['actualAddress'] ?? null,
                'metadata' => [
                    'raw_data' => $counterparty,
                    'synced_at' => now()->toIso8601String(),
                ],
            ]
        );
    }
}

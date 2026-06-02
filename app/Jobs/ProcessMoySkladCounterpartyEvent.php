<?php
// app/Jobs/ProcessMoySkladCounterpartyEvent.php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bot;
use App\Models\Client;
use App\Models\Integration;
use App\Services\MoySkladService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMoySkladCounterpartyEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Bot $bot,
        public array $payload,
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        try {
            // Get MoySklad integration
            $integration = $this->bot->integrations()
                ->where('type', 'moysklad')
                ->where('is_active', true)
                ->first();

            if (!$integration) {
                \Log::warning('MoySklad integration not found for bot', ['bot_id' => $this->bot->id]);
                return;
            }

            $token = decrypt($integration->credentials['api_token']);
            $service = new MoySkladService($token);

            // Determine event type and counterparty ID
            $eventType = $this->payload['eventType'] ?? null;
            $counterpartyId = $this->extractCounterpartyId();

            if (!$counterpartyId) {
                \Log::warning('Could not extract counterparty ID from webhook', ['payload' => $this->payload]);
                return;
            }

            // Only process counterparty creation/update events
            if (!in_array($eventType, ['entity.counterparty.create', 'entity.counterparty.update'])) {
                return;
            }

            // Fetch counterparty details
            $counterparty = $service->getCounterpartyById($counterpartyId);

            if (!$counterparty) {
                \Log::warning('Could not fetch counterparty from MoySklad', ['counterparty_id' => $counterpartyId]);
                return;
            }

            // Create or update client
            $this->syncCounterparty($counterparty);

            \Log::info('MoySklad counterparty synced', [
                'bot_id' => $this->bot->id,
                'counterparty_id' => $counterpartyId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to process MoySklad counterparty event', [
                'bot_id' => $this->bot->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function extractCounterpartyId(): ?string
    {
        // Try to extract from different webhook payload formats
        if (isset($this->payload['id'])) {
            return $this->payload['id'];
        }

        if (isset($this->payload['counterpartyId'])) {
            return $this->payload['counterpartyId'];
        }

        if (isset($this->payload['entity']['id'])) {
            return $this->payload['entity']['id'];
        }

        return null;
    }

    private function syncCounterparty(array $counterparty): void
    {
        $phone = null;

        // Extract phone from phone array if present
        if (!empty($counterparty['phones']) && is_array($counterparty['phones'])) {
            $phone = $counterparty['phones'][0]['number'] ?? null;
        }

        Client::updateOrCreate(
            [
                'bot_id' => $this->bot->id,
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

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('MoySklad counterparty event processing failed permanently', [
            'bot_id' => $this->bot->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

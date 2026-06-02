<?php
// app/Jobs/ProcessMoySkladEvent.php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMoySkladEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Bot $bot,
        public string $eventType,
        public array $payload,
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        try {
            $eventClass = $this->resolveEventHandler($this->eventType);

            if (!$eventClass) {
                \Log::info('No handler for MoySklad event type', [
                    'bot_id' => $this->bot->id,
                    'eventType' => $this->eventType,
                ]);
                return;
            }

            // Dispatch to specific handler
            call_user_func([$eventClass, 'handle'], $this->bot, $this->payload);

            \Log::info('MoySklad event processed', [
                'bot_id' => $this->bot->id,
                'eventType' => $this->eventType,
                'handler' => $eventClass,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to process MoySklad event', [
                'bot_id' => $this->bot->id,
                'eventType' => $this->eventType,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function resolveEventHandler(string $eventType): ?string
    {
        $handlers = [
            'entity.counterparty.create' => 'App\Handlers\MoySklad\CounterpartyCreatedHandler',
            'entity.counterparty.update' => 'App\Handlers\MoySklad\CounterpartyUpdatedHandler',
            'entity.demand.create' => 'App\Handlers\MoySklad\DemandCreatedHandler',
            'entity.demand.update' => 'App\Handlers\MoySklad\DemandUpdatedHandler',
            'entity.supply.create' => 'App\Handlers\MoySklad\SupplyCreatedHandler',
            'entity.supply.update' => 'App\Handlers\MoySklad\SupplyUpdatedHandler',
            'entity.paymentin.create' => 'App\Handlers\MoySklad\PaymentInCreatedHandler',
            'entity.paymentout.create' => 'App\Handlers\MoySklad\PaymentOutCreatedHandler',
        ];

        return $handlers[$eventType] ?? null;
    }

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('MoySklad event processing failed permanently', [
            'bot_id' => $this->bot->id,
            'eventType' => $this->eventType,
            'error' => $exception->getMessage(),
        ]);
    }
}

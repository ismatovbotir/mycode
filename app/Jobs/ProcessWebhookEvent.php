<?php

namespace App\Jobs;

use App\Models\Bot;
use App\Models\BotClient;
use App\Models\Notification;
use App\Models\WebhookEvent;
use App\Services\DeveloperNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWebhookEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public WebhookEvent $event) {}

    public function handle(): void
    {
        $bot = $this->event->bot;
        $payload = $this->event->payload;
        $eventType = $this->event->event_type;

        $template = $bot->eventTemplates()
            ->where('event_type', $eventType)
            ->first();

        if (!$template) {
            $this->event->update(['status' => 'failed']);
            $notifier = new DeveloperNotificationService();
            $notifier->notifyWebhookError($bot->name, "No template found for event type: $eventType");
            return;
        }

        $customers = $payload['customers'] ?? [];
        $notificationCount = 0;

        foreach ($customers as $customer) {
            $mySkladId = $customer['id'] ?? null;

            if (!$mySkladId) {
                continue;
            }

            $botClient = $bot->clients()
                ->whereHas('tgUser', fn($q) => $q->where('matched', true))
                ->where('mySklad_id', $mySkladId)
                ->first();

            if (!$botClient) {
                continue;
            }

            $lang = $botClient->tgUser->lang ?? 'ru';
            $messageTemplate = $template->messages[$lang] ?? $template->messages['ru'] ?? '';

            $message = $this->interpolateMessage($messageTemplate, $payload);

            $notification = Notification::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'bot_id' => $bot->id,
                'bot_client_id' => $botClient->id,
                'message' => $message,
                'tg_status' => 'queued',
            ]);

            SendTelegramNotification::dispatch($notification)->onQueue('telegram');
            $notificationCount++;
        }

        $this->event->update(['status' => 'sent']);

        if ($notificationCount > 0) {
            $notifier = new DeveloperNotificationService();
            $notifier->notifyWebhookReceived($bot->name, $eventType, [
                'customers_count' => count($customers),
                'notifications_queued' => $notificationCount,
                'payload_summary' => substr(json_encode($payload), 0, 200),
            ]);
        }
    }

    private function interpolateMessage(string $template, array $payload): string
    {
        $message = $template;

        $replacements = [
            '{amount}' => $payload['amount'] ?? '',
            '{order_number}' => $payload['order_number'] ?? $payload['number'] ?? '',
            '{date}' => $payload['date'] ?? now()->format('Y-m-d'),
        ];

        foreach ($replacements as $placeholder => $value) {
            $message = str_replace($placeholder, (string)$value, $message);
        }

        return $message;
    }

    public function backoff(): array
    {
        return [10, 60, 300];
    }
}

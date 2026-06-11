<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\WebhookEvent;
use App\Services\DeveloperNotificationService;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendDocumentNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(private string $webhookEventId) {}

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function handle(): void
    {
        $event = WebhookEvent::findOrFail($this->webhookEventId);

        if (!$event->matched || !$event->bot_client_id) {
            $event->update(['tg_status' => 'failed']);
            return;
        }

        $botClient = $event->botClient;
        $bot = $botClient?->bot;

        if (!$bot || !$botClient) {
            $event->update(['tg_status' => 'failed']);
            return;
        }

        try {
            $token = decrypt($bot->tg_bot_token);
            $tgService = new TelegramService($token);

            $chatId = (int) $botClient->tg_user_id;
            $message = $event->payload['formatted_message'] ?? 'Document notification';

            $tgService->sendMessage($token, $chatId, $message);

            $event->update([
                'tg_status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::channel('telegram')->info('Document notification sent', [
                'webhook_event_id' => $event->id,
                'bot_client_id' => $botClient->id,
                'chat_id' => $chatId,
            ]);

            $notifier = new DeveloperNotificationService();
            $notifier->notifyMessageSent(
                $bot->name,
                $botClient->tgUser->first_name . ' ' . $botClient->tgUser->last_name,
                substr($message, 0, 150),
                true
            );
        } catch (Throwable $e) {
            Log::error('Failed to send document notification', [
                'webhook_event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        $event = WebhookEvent::find($this->webhookEventId);
        if ($event) {
            $event->update(['tg_status' => 'failed']);
        }

        Log::error('SendDocumentNotification failed permanently', [
            'webhook_event_id' => $this->webhookEventId,
            'error' => $e->getMessage(),
        ]);

        $notifier = new DeveloperNotificationService();
        $notifier->notifyJobFailed(
            'SendDocumentNotification',
            $event?->bot?->name ?? 'Unknown Bot',
            $e->getMessage()
        );
    }
}

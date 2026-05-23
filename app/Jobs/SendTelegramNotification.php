<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Notification $notification) {}

    public function handle(): void
    {
        $botClient = $this->notification->botClient;
        $bot = $this->notification->bot;
        $tgUser = $botClient->tgUser;

        $token = decrypt($bot->tg_bot_token);
        $chatId = $tgUser->chat_id;

        $telegramService = new TelegramService();

        try {
            $response = $telegramService->sendMessage(
                $token,
                $chatId,
                $this->notification->message
            );

            if ($response['ok'] ?? false) {
                $this->notification->update([
                    'tg_status' => 'sent',
                    'sent_at' => now(),
                ]);

                Log::channel('telegram')->info('Message sent', [
                    'bot_uuid' => $bot->uuid,
                    'chat_id' => $chatId,
                    'notif_uuid' => $this->notification->uuid,
                ]);
            } else {
                $this->notification->update(['tg_status' => 'failed']);
                $this->fail(new \Exception('Telegram API error: ' . json_encode($response)));
            }
        } catch (\Exception $e) {
            Log::channel('telegram')->error('Failed to send message', [
                'bot_uuid' => $bot->uuid,
                'chat_id' => $chatId,
                'error' => $e->getMessage(),
            ]);

            $this->fail($e);
        }
    }

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function failed(\Throwable $exception): void
    {
        $this->notification->update(['tg_status' => 'failed']);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Bot;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {bot_uuid}';
    protected $description = 'Set Telegram webhook for a bot';

    public function handle(): int
    {
        $botUuid = $this->argument('bot_uuid');
        $bot = Bot::where('uuid', $botUuid)->firstOrFail();

        $telegramService = new TelegramService();
        $token = decrypt($bot->tg_bot_token);
        $webhookUrl = route('telegram.webhook', ['bot' => $bot->uuid], true);

        $success = $telegramService->setWebhook($token, $webhookUrl);

        if ($success) {
            $this->info("Webhook set successfully for bot: {$bot->name}");
            $this->line("URL: {$webhookUrl}");
            return 0;
        }

        $this->error("Failed to set webhook");
        return 1;
    }
}

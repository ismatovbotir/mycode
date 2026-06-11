<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DeveloperNotificationService;
use Illuminate\Console\Command;

class TestNotificationBot extends Command
{
    protected $signature = 'notify:test {--message=Test message from development}';
    protected $description = 'Test the developer notification bot';

    public function handle(): int
    {
        $this->info('Testing Developer Notification Bot...');

        $botToken = env('bot_token');
        $chatId = env('bot_chat_id');

        if (!$botToken || !$chatId) {
            $this->error('❌ Missing credentials in .env');
            $this->line('  bot_token: ' . ($botToken ? '✓ Set' : '✗ Missing'));
            $this->line('  bot_chat_id: ' . ($chatId ? '✓ Set' : '✗ Missing'));
            return 1;
        }

        $this->info('✓ Credentials found in .env');
        $this->line("  bot_token: {$botToken}");
        $this->line("  bot_chat_id: {$chatId}");

        $message = $this->option('message');
        $this->info("\nSending test message: \"$message\"");

        $notifier = new DeveloperNotificationService();
        $notifier->notifyDevelopment(
            'Test Notification',
            $message,
            ['timestamp' => now()->toIso8601String()]
        );

        $this->info('✓ Notification sent! Check your Telegram.');

        return 0;
    }
}

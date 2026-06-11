<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Bot;
use App\Services\TelegramService;
use Illuminate\Console\Command;

class CheckTelegramWebhooks extends Command
{
    protected $signature = 'telegram:webhooks {--verbose}';
    protected $description = 'Check Telegram webhook status for all bots';

    public function handle(): int
    {
        $this->info('🔍 Checking Telegram Webhooks...');
        $this->newLine();

        $bots = Bot::all();

        if ($bots->isEmpty()) {
            $this->warn('No bots found in database');
            return 1;
        }

        $telegramService = new TelegramService();
        $hasIssues = false;

        foreach ($bots as $bot) {
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("🤖 <info>{$bot->name}</info> (@{$bot->tg_username})");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            try {
                $token = decrypt($bot->tg_bot_token);

                // Get webhook info
                $webhookInfo = $telegramService->getWebhookInfo($token);

                if (!$webhookInfo['success']) {
                    $this->error("❌ Failed to get webhook info: " . ($webhookInfo['message'] ?? 'Unknown error'));
                    $hasIssues = true;
                    continue;
                }

                $info = $webhookInfo['result'];

                // Display webhook status
                if (isset($info['url']) && !empty($info['url'])) {
                    $this->info("✅ Webhook configured");
                    $this->line("   URL: <comment>{$info['url']}</comment>");
                } else {
                    $this->warn("⚠️  No webhook configured");
                    $hasIssues = true;
                }

                // Pending updates
                $pending = $info['pending_update_count'] ?? 0;
                if ($pending > 0) {
                    $this->warn("📬 Pending updates: <comment>{$pending}</comment>");
                } else {
                    $this->info("✓ No pending updates");
                }

                // Last error
                if (isset($info['last_error_date']) && $info['last_error_date'] > 0) {
                    $lastError = date('Y-m-d H:i:s', $info['last_error_date']);
                    $this->error("❌ Last error at: <comment>{$lastError}</comment>");
                    if ($info['last_error_message'] ?? false) {
                        $this->error("   Message: <comment>{$info['last_error_message']}</comment>");
                    }
                    $hasIssues = true;
                } else {
                    $this->info("✓ No recent errors");
                }

                // IP whitelist
                if (isset($info['allowed_updates'])) {
                    $this->line("   Allowed updates: <comment>" . implode(', ', $info['allowed_updates']) . "</comment>");
                }

                // Database status
                $this->line("   DB Status: <comment>" . ($bot->webhook_status ? '✓ Configured' : '✗ Not marked as configured') . "</comment>");
                $this->line("   Bot Status: <comment>" . ($bot->is_active ? '🟢 Active' : '⚫ Inactive') . "</comment>");
                $this->line("   Clients: <comment>{$bot->clients->count()}</comment>");

            } catch (\Exception $e) {
                $this->error("❌ Error: " . $e->getMessage());
                $hasIssues = true;
            }

            $this->newLine();
        }

        if ($hasIssues) {
            $this->warn('⚠️  Issues detected. Run with --verbose for more details.');
            return 1;
        }

        $this->info('✅ All webhooks are healthy!');
        return 0;
    }
}

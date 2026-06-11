<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Bot;
use App\Models\BotClient;
use App\Models\TgUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestWebhook extends Command
{
    protected $signature = 'webhook:test {bot_id} {--action=start}';
    protected $description = 'Test webhook flow: /start → language → name → phone';

    public function handle(): int
    {
        $botId = $this->argument('bot_id');
        $action = $this->option('action');

        $bot = Bot::find($botId);
        if (!$bot) {
            $this->error("Bot not found: $botId");
            return 1;
        }

        $this->info("Testing webhook for bot: {$bot->name}");
        $this->line("Bot ID: {$bot->id}");
        $this->line("Bot Telegram ID: {$bot->tg_bot_id}");

        $testTgUserId = '999999999';
        $chatId = 999999999;

        $this->line("\n=== Database State Before ===");
        $this->checkDatabase($botId, $testTgUserId);

        if ($action === 'all') {
            $this->simulateStart($testTgUserId, $chatId);
            $this->simulateLanguage($testTgUserId, $chatId);
            $this->simulateName($testTgUserId, $chatId);
            $this->simulatePhone($testTgUserId, $chatId, $bot);
        }

        $this->line("\n=== Database State After ===");
        $this->checkDatabase($botId, $testTgUserId);

        $this->line("\n=== Logs ===");
        $this->showLogs();

        return 0;
    }

    private function checkDatabase(string $botId, string $tgUserId): void
    {
        $tgUser = TgUser::find($tgUserId);
        $botClient = BotClient::where('bot_id', $botId)
            ->where('tg_user_id', $tgUserId)
            ->first();

        $this->line("TgUser (ID: $tgUserId):");
        if ($tgUser) {
            $this->line("  ✓ Found");
            $this->line("    - first_name: {$tgUser->first_name}");
            $this->line("    - last_name: {$tgUser->last_name}");
            $this->line("    - phone: {$tgUser->phone}");
            $this->line("    - lang: {$tgUser->lang}");
        } else {
            $this->line("  ✗ Not found");
        }

        $this->line("\nBotClient (Bot: $botId, TgUser: $tgUserId):");
        if ($botClient) {
            $this->line("  ✓ Found");
            $this->line("    - ID: {$botClient->id}");
            $this->line("    - approved: " . ($botClient->approved ? 'true' : 'false'));
            $this->line("    - created_at: {$botClient->created_at}");
        } else {
            $this->line("  ✗ Not found");
        }
    }

    private function simulateStart(string $tgUserId, int $chatId): void
    {
        $this->line("\n[1] Simulating /start message");
        $tgUser = TgUser::firstOrCreate(
            ['id' => $tgUserId],
            ['lang' => 'uz']
        );
        $this->line("    TgUser: {$tgUser->id}");
    }

    private function simulateLanguage(string $tgUserId, int $chatId): void
    {
        $this->line("[2] Simulating language selection");
        TgUser::where('id', $tgUserId)->update(['lang' => 'ru']);
        $this->line("    Updated lang to: ru");
    }

    private function simulateName(string $tgUserId, int $chatId): void
    {
        $this->line("[3] Simulating name input");
        TgUser::where('id', $tgUserId)->update([
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
        $this->line("    Updated name to: Test User");
    }

    private function simulatePhone(string $tgUserId, int $chatId, Bot $bot): void
    {
        $this->line("[4] Simulating phone input (creating BotClient)");

        $tgUser = TgUser::where('id', $tgUserId)->first();
        if (!$tgUser) {
            $this->error("    TgUser not found!");
            return;
        }

        TgUser::where('id', $tgUserId)->update(['phone' => '+998901234567']);
        $this->line("    Updated phone");

        $approved = !$bot->requires_admin_approval;
        $this->line("    Creating BotClient...");
        $this->line("      - bot_id: {$bot->id}");
        $this->line("      - tg_user_id: {$tgUserId}");
        $this->line("      - approved: " . ($approved ? 'true' : 'false'));

        try {
            $botClient = BotClient::updateOrCreate(
                ['bot_id' => $bot->id, 'tg_user_id' => $tgUserId],
                [
                    'approved' => $approved,
                    'approved_at' => $approved ? now() : null,
                ]
            );
            $this->line("    ✓ BotClient created: {$botClient->id}");
        } catch (\Exception $e) {
            $this->error("    ✗ Error: " . $e->getMessage());
        }
    }

    private function showLogs(): void
    {
        $logFile = storage_path('logs/telegram.log');
        if (file_exists($logFile)) {
            $this->line("\nRecent telegram logs:");
            $lines = array_slice(explode("\n", file_get_contents($logFile)), -20);
            foreach ($lines as $line) {
                if (trim($line)) {
                    $this->line($line);
                }
            }
        }
    }
}

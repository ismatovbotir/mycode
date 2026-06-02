<?php
// app/Console/Commands/SendDailyReports.php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\SendDailyBotReport;
use App\Models\Bot;
use Illuminate\Console\Command;

class SendDailyReports extends Command
{
    protected $signature = 'reports:send-daily {--time=08:00 : Time to send reports (HH:MM)}';

    protected $description = 'Send daily reports to bot owners';

    public function handle(): int
    {
        $bots = Bot::where('is_active', true)->get();

        if ($bots->isEmpty()) {
            $this->info('No active bots found.');
            return 0;
        }

        $count = 0;
        foreach ($bots as $bot) {
            try {
                SendDailyBotReport::dispatch($bot)
                    ->onQueue('telegram');
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed to queue report for bot {$bot->name}: {$e->getMessage()}");
            }
        }

        $this->info("Queued {$count} daily reports for sending.");
        return 0;
    }
}

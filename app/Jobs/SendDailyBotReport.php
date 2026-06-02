<?php
// app/Jobs/SendDailyBotReport.php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Bot;
use App\Services\BotReportService;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDailyBotReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Bot $bot,
    ) {
        $this->onQueue('telegram');
    }

    public function handle(): void
    {
        try {
            // Get all owner clients
            $owners = $this->bot->botClients()
                ->where('is_owner', true)
                ->with('tgUser')
                ->get();

            if ($owners->isEmpty()) {
                \Log::info('No owner clients found for report', ['bot_id' => $this->bot->id]);
                return;
            }

            // Generate report
            $reportService = new BotReportService();
            $report = $reportService->generateDailyReport($this->bot);
            $message = $reportService->formatReportMessage($report);

            // Send to all owners
            $telegramService = new TelegramService();
            $token = decrypt($this->bot->tg_bot_token);

            foreach ($owners as $owner) {
                try {
                    $telegramService->sendMessage(
                        $token,
                        (int) $owner->tgUser->id,
                        $message,
                        'HTML'
                    );

                    \Log::info('Daily report sent to owner', [
                        'bot_id' => $this->bot->id,
                        'tg_user_id' => $owner->tgUser->id,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send report to owner', [
                        'bot_id' => $this->bot->id,
                        'tg_user_id' => $owner->tgUser->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Daily report job failed', [
                'bot_id' => $this->bot->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error('Daily report job failed permanently', [
            'bot_id' => $this->bot->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

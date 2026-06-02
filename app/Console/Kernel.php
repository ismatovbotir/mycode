<?php
// app/Console/Kernel.php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Send daily reports to bot owners at 8:00 AM
        $schedule->command('reports:send-daily')
            ->dailyAt('08:00')
            ->timezone('Asia/Tashkent');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

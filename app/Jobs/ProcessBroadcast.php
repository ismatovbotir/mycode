<?php
// app/Jobs/ProcessBroadcast.php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Broadcast;
use App\Services\BroadcastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBroadcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Broadcast $broadcast) {}

    public function handle(BroadcastService $broadcastService): void
    {
        if ($this->broadcast->bot->company->status === 'suspended') {
            $this->broadcast->update(['status' => 'failed']);
            return;
        }

        $broadcastService->send($this->broadcast);
        $this->broadcast->update(['status' => 'sent']);
    }

    public function backoff(): array
    {
        return [10, 60, 300];
    }

    public function failed(\Throwable $exception): void
    {
        $this->broadcast->update(['status' => 'failed']);
    }
}

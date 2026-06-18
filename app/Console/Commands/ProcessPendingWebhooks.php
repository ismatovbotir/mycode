<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessMoySkladWebhook;
use App\Models\MoySkladWebhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPendingWebhooks extends Command
{
    protected $signature = 'webhooks:process {--limit=50 : Maximum webhooks to process}';
    protected $description = 'Process pending MoySkład webhooks';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $this->info("Processing pending webhooks (max: {$limit})...");

        try {
            $webhooks = MoySkladWebhook::where('status', 'processing')
                ->orderBy('created_at', 'asc')
                ->limit($limit)
                ->get();

            if ($webhooks->isEmpty()) {
                $this->info('No pending webhooks to process');
                return 0;
            }

            $count = $webhooks->count();
            $this->info("Found {$count} webhook(s) to process");

            foreach ($webhooks as $webhook) {
                ProcessMoySkladWebhook::dispatch($webhook)
                    ->onQueue('default');

                $this->line("  ✓ Dispatched webhook: {$webhook->id}");
            }

            Log::channel('webhook')->info("Dispatched {$count} webhooks for processing", [
                'command' => 'ProcessPendingWebhooks',
            ]);

            $this->info("✓ Dispatched {$count} webhook job(s)");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error processing webhooks: {$e->getMessage()}");
            Log::channel('webhook')->error('ProcessPendingWebhooks command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}

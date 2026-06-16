<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\UserEntity;
use App\Models\MoySkladWebhook;
use Illuminate\Console\Command;

class DebugMoySkładWebhooks extends Command
{
    protected $signature = 'moysklad:debug';
    protected $description = 'Debug MoySkład webhook configuration';

    public function handle(): int
    {
        $this->info('🔍 Debugging MoySkład Webhooks...');
        $this->newLine();

        // Check UserEntities
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('📋 USER ENTITIES');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $userEntities = UserEntity::with(['bot', 'entity'])->get();

        if ($userEntities->isEmpty()) {
            $this->warn('❌ No UserEntities found!');
            $this->line('');
            $this->info('To create UserEntities:');
            $this->line('1. Go to /bots → Entities');
            $this->line('2. Activate an entity (demand, supply, etc.)');
            $this->line('3. This will create UserEntity + MoySkład webhooks');
        } else {
            foreach ($userEntities as $ue) {
                $this->line("✓ ID: {$ue->id}");
                $this->line("  Bot: {$ue->bot->name}");
                $this->line("  Entity: {$ue->entity->type}");
                $this->line("  Action: {$ue->action}");
                $this->line("  MoySkład Webhook ID: {$ue->ms_id}");
                $this->line("");
            }
        }

        // Check MoySkład Webhooks
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('🔗 MOYSKLAD WEBHOOKS (Received)');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $webhooks = MoySkladWebhook::all();

        if ($webhooks->isEmpty()) {
            $this->warn('⚠️  No webhooks received yet from MoySkład');
            $this->line('');
            $this->info('Webhook URL should be:');
            $this->line('<comment>https://mycode.uz/api/webhook/ms/{user_entity_id}</comment>');
            $this->newLine();
            $this->info('To test, make a POST request:');
            $this->line("<comment>curl -X POST https://mycode.uz/api/webhook/ms/{user_entity_id} \\");
            $this->line("  -H 'Content-Type: application/json' \\");
            $this->line("  -d '{\"action\": \"CREATE\", \"id\": \"test-doc-123\"}'</comment>");
        } else {
            $this->info("Found {$webhooks->count()} webhooks:");
            $this->newLine();

            foreach ($webhooks as $wh) {
                $this->line("ID: {$wh->id}");
                $this->line("  Webhook ID: {$wh->webhook_id}");
                $this->line("  Entity Type: {$wh->entity_type}");
                $this->line("  Action: {$wh->event_type}");
                $this->line("  Status: {$wh->status}");
                $this->line("  Created: {$wh->created_at}");
                if ($wh->status === 'failed') {
                    $this->error("  Error: {$wh->error_message}");
                }
                $this->line("");
            }
        }

        // Summary
        $this->newLine();
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line('📊 SUMMARY');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line("UserEntities: " . $userEntities->count());
        $this->line("Webhooks Received: " . $webhooks->count());
        $this->line("Status Breakdown:");
        $this->line("  - received: " . MoySkladWebhook::where('status', 'received')->count());
        $this->line("  - processing: " . MoySkladWebhook::where('status', 'processing')->count());
        $this->line("  - processed: " . MoySkladWebhook::where('status', 'processed')->count());
        $this->line("  - failed: " . MoySkladWebhook::where('status', 'failed')->count());

        return 0;
    }
}

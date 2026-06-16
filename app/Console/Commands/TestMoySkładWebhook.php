<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\UserEntity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestMoySkładWebhook extends Command
{
    protected $signature = 'moysklad:test {user_entity_id?} {--action=CREATE}';
    protected $description = 'Send a test webhook to your MoySkład webhook endpoint';

    public function handle(): int
    {
        $userEntityId = $this->argument('user_entity_id');
        $action = $this->option('action');

        // If no user_entity_id provided, show available ones
        if (!$userEntityId) {
            $this->info('📋 Available UserEntities:');
            $userEntities = UserEntity::with(['entity'])->get();

            if ($userEntities->isEmpty()) {
                $this->error('No UserEntities found! Create one first via the Entities page.');
                return 1;
            }

            foreach ($userEntities as $ue) {
                $this->line("  {$ue->id} - {$ue->entity->type} ({$ue->action})");
            }

            $userEntityId = $this->ask('Enter UserEntity ID to test');
        }

        $userEntity = UserEntity::find($userEntityId);
        if (!$userEntity) {
            $this->error("UserEntity not found: $userEntityId");
            return 1;
        }

        $this->info("Testing MoySkład webhook...");
        $this->line("UserEntity: {$userEntity->id}");
        $this->line("Entity Type: {$userEntity->entity->type}");
        $this->line("Action: {$action}");

        // Test payload (simplified)
        $payload = [
            'action' => $action,
            'id' => 'test-doc-' . now()->timestamp,
            'meta' => [
                'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/demand/00000000-0000-0000-0000-000000000000',
            ],
            'name' => 'Test Document',
        ];

        $this->line('');
        $this->info('📤 Sending webhook...');

        try {
            $response = Http::post("https://mycode.uz/api/webhook/ms/{$userEntityId}", $payload);

            $this->newLine();
            if ($response->successful()) {
                $this->info('✅ Webhook sent successfully!');
                $this->line('Status: ' . $response->status());
                $this->line('Response: ' . json_encode($response->json(), JSON_PRETTY_PRINT));
            } else {
                $this->error('❌ Webhook failed!');
                $this->line('Status: ' . $response->status());
                $this->line('Response: ' . $response->body());
            }

            // Check if webhook was recorded
            $this->newLine();
            $this->info('📊 Checking webhook record...');
            $webhook = \App\Models\MoySkladWebhook::latest()->first();

            if ($webhook) {
                $this->info('✓ Webhook recorded in database!');
                $this->line("  ID: {$webhook->id}");
                $this->line("  Status: {$webhook->status}");
                $this->line("  Created: {$webhook->created_at}");
            } else {
                $this->warn('⚠️  Webhook not recorded in database');
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}

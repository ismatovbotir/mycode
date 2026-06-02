<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Entity;
use App\Models\UserEntity;
use Livewire\Component;

class MoySkladWebhooks extends Component
{
    public ?string $selectedToken = null;
    public bool $showActivateModal = false;
    public ?Entity $selectedEntity = null;

    public function getInactiveEntitiesProperty()
    {
        return Entity::whereNotExists(function ($query) {
            $query->select('id')
                ->from('user_entities')
                ->whereColumn('entity_id', 'entities.id')
                ->where('ms_id', '!=', null);
        })->get();
    }

    public function getActiveEntitiesProperty()
    {
        return UserEntity::with(['entity', 'user'])
            ->whereNotNull('ms_id')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openActivateModal(Entity $entity): void
    {
        $this->selectedEntity = $entity;
        $this->showActivateModal = true;
    }

    public function activateEntity(): void
    {
        if (!$this->selectedEntity || !$this->selectedToken) {
            $this->addError('token', 'Bearer token is required');
            return;
        }

        try {
            // Create webhooks for all three actions
            $webhookCreateId = $this->createWebhook('CREATE');
            $webhookUpdateId = $this->createWebhook('UPDATE');
            $webhookDeleteId = $this->createWebhook('DELETE');

            // Save each action to user_entities
            foreach (['CREATE' => $webhookCreateId, 'UPDATE' => $webhookUpdateId, 'DELETE' => $webhookDeleteId] as $action => $ms_id) {
                UserEntity::updateOrCreate(
                    [
                        'entity_id' => $this->selectedEntity->id,
                        'action' => $action,
                    ],
                    [
                        'ms_id' => $ms_id,
                    ]
                );
            }

            session()->flash('success', "✓ {$this->selectedEntity->name} webhooks activated successfully!");

            $this->showActivateModal = false;
            $this->selectedEntity = null;
            $this->selectedToken = null;
        } catch (\Exception $e) {
            $this->addError('activation', 'Failed to activate webhooks: ' . $e->getMessage());
        }
    }

    public function deactivateEntity(Entity $entity): void
    {
        try {
            if (!$this->selectedToken) {
                $this->addError('token', 'Bearer token required to deactivate');
                return;
            }

            // Get all webhook IDs for this entity
            $webhooks = UserEntity::where('entity_id', $entity->id)
                ->whereNotNull('ms_id')
                ->get();

            // Delete webhooks from МойСклад
            foreach ($webhooks as $webhook) {
                $this->deleteWebhook($webhook->ms_id);
                $webhook->update(['ms_id' => null]);
            }

            session()->flash('success', "✓ {$entity->name} webhooks deactivated!");
        } catch (\Exception $e) {
            $this->addError('deactivation', 'Failed to deactivate: ' . $e->getMessage());
        }
    }

    private function createWebhook(string $action): ?string
    {
        $url = config('app.url') . '/api/webhook/moysklad/' . $this->selectedEntity->uuid;

        $payload = [
            'url' => $url,
            'action' => $action,
            'entityType' => $this->selectedEntity->type,
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => "Bearer {$this->selectedToken}",
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'gzip',
        ])->post('https://api.moysklad.ru/api/remap/1.2/entity/webhook', $payload);

        if ($response->successful()) {
            return $response->json('id');
        }

        throw new \Exception("Failed to create {$action} webhook: " . $response->body());
    }

    private function deleteWebhook(string $webhookId): void
    {
        if (!$this->selectedToken || !$webhookId) {
            return;
        }

        \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => "Bearer {$this->selectedToken}",
            'Accept' => 'application/json',
        ])->delete("https://api.moysklad.ru/api/remap/1.2/entity/webhook/{$webhookId}");
    }

    public function render()
    {
        return view('livewire.admin.moysklad-webhooks', [
            'inactiveEntities' => $this->inactiveEntities,
            'activeEntities' => $this->activeEntities,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Entity;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Entities extends Component
{
    public ?int $selectedEntityId = null;
    public string $showActivateModal = '';
    public bool $isActivating = false;
    public array $activationSteps = [];
    public int $currentStep = 0;

    public function getAvailableEntitiesProperty()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        $userEntityIds = $user->entities()
            ->pluck('entity_id')
            ->toArray();

        return Entity::where('is_active', true)
            ->whereNotIn('id', $userEntityIds)
            ->orderBy('type')
            ->get();
    }

    public function getActiveSystemEntitiesProperty()
    {
        return Entity::where('is_active', true)
            ->orderBy('type')
            ->get();
    }

    public function getUserEntitiesProperty()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return collect();
        }

        return $user->entities()
            ->with('entity')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function openActivateModal(int $entityId): void
    {
        $this->selectedEntityId = $entityId;
        $this->showActivateModal = 'true';
    }

    public function closeActivateModal(): void
    {
        $this->selectedEntityId = null;
        $this->showActivateModal = '';
    }

    public function activateEntity(): void
    {
        if (!$this->selectedEntityId) {
            return;
        }

        $entity = Entity::findOrFail($this->selectedEntityId);
        $user = Auth::user();

        if (!$user || !$user->moysklad_token) {
            $this->dispatch('error', message: 'МойСклад token not configured. Please set it up first.');
            return;
        }

        // Initialize activation steps
        $this->isActivating = true;
        $this->currentStep = 0;
        $this->activationSteps = [
            ['action' => 'CREATE', 'status' => 'pending', 'message' => 'Creating webhook for CREATE action...'],
            ['action' => 'UPDATE', 'status' => 'pending', 'message' => 'Creating webhook for UPDATE action...'],
            ['action' => 'DELETE', 'status' => 'pending', 'message' => 'Creating webhook for DELETE action...'],
        ];

        try {
            $this->createMoySkladWebhooks($entity, $user);
            session()->flash('success', "Entity '{$entity->type}' activated successfully");
            $this->closeActivateModal();
            $this->isActivating = false;
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->activationSteps[$this->currentStep]['status'] = 'failed';
            $this->activationSteps[$this->currentStep]['message'] = 'Failed: ' . $e->getMessage();
            $this->isActivating = false;
        }
    }

    private function createMoySkladWebhooks(Entity $entity, User $user): void
    {
        if (!$user->moysklad_token) {
            throw new \InvalidArgumentException('MoySklad token is not configured for the user');
        }

        // moysklad_token is cast as 'encrypted', so it's already decrypted when accessed
        /** @var string $token */
        $token = $user->moysklad_token;
        $moySkladService = new \App\Services\MoySkladService($token);

        // Create user_entity records and webhooks for each action type
        foreach (['CREATE', 'UPDATE', 'DELETE'] as $index => $action) {
            $this->currentStep = $index;

            try {
                // 1. Create user_entity record
                $userEntity = new \App\Models\UserEntity([
                    'user_id' => $user->id,
                    'entity_id' => $entity->id,
                    'action' => $action,
                ]);
                $userEntity->save();

                // 2. Generate webhook URL using the created user_entity ID
                $webhookUrl = route('webhook.moysklad.entity', ['user_entity' => $userEntity->id], absolute: true);

                // 3. Create webhook on МойСклад
                $result = $moySkladService->createWebhook($entity->type, $webhookUrl, $action);

                if (!$result || !isset($result['id'])) {
                    $userEntity->delete();
                    throw new \Exception("Failed to create {$action} webhook for {$entity->type}");
                }

                // 4. Update user_entity record with webhook ID
                $userEntity->update([
                    'ms_id' => $result['id'],
                    'messages' => json_encode(['webhook_response' => $result]),
                ]);

                // Mark step as completed
                $this->activationSteps[$index]['status'] = 'completed';
                $this->activationSteps[$index]['message'] = "✓ {$action} webhook created (ID: {$result['id']})";
            } catch (\Exception $e) {
                $this->activationSteps[$index]['status'] = 'failed';
                $this->activationSteps[$index]['message'] = "✗ {$action}: " . $e->getMessage();
                throw $e;
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.entities', [
            'availableEntities' => $this->availableEntities,
            'activeSystemEntities' => $this->activeSystemEntities,
            'userEntities' => $this->userEntities,
        ]);
    }
}

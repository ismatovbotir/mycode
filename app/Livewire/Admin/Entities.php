<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Entity;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Entities extends Component
{
    public string $bearer_token = '';
    public bool $testing = false;
    public ?string $test_message = null;
    public bool $test_passed = false;
    public bool $showRetryModal = false;
    public ?string $retryingUserEntityId = null;
    public ?string $retryCommand = null;
    public ?string $retryUrl = null;
    public ?array $retryPayload = null;
    public ?array $retryResponse = null;

    public function getAllEntitiesProperty()
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

    public function activateEntity(int $entityId): void
    {
        $entity = Entity::findOrFail($entityId);
        $user = Auth::user();

        if (!$user || !$user->moysklad_token) {
            session()->flash('error', 'МойСклад token not configured. Please set it up first.');
            return;
        }

        try {
            $this->createMoySkladWebhooks($entity, $user);
            session()->flash('success', "Entity '{$entity->type}' activated successfully!");
        } catch (\Exception $e) {
            session()->flash('error', "Failed to activate: " . $e->getMessage());
        }
    }

    public function openRetryModal(string $userEntityId): void
    {
        /** @var \App\Models\UserEntity $userEntity */
        $userEntity = \App\Models\UserEntity::findOrFail($userEntityId);

        $this->retryingUserEntityId = $userEntityId;
        $this->retryCommand = $userEntity->action;
        $this->retryUrl = route('webhook.moysklad.entity', ['user_entity' => $userEntity->id], absolute: true);
        $this->retryPayload = [
            'url' => $this->retryUrl,
            'action' => $userEntity->action,
            'entityType' => $userEntity->entity->type,
        ];
        $this->showRetryModal = true;

        // Auto-execute retry after modal is shown
        $this->dispatch('executeRetry', userEntityId: $userEntityId);
    }

    public function closeRetryModal(): void
    {
        $this->showRetryModal = false;
        $this->retryingUserEntityId = null;
        $this->retryCommand = null;
        $this->retryUrl = null;
        $this->retryPayload = null;
        $this->retryResponse = null;
    }

    public function executeRetry(string $userEntityId): void
    {
        /** @var \App\Models\UserEntity $userEntity */
        $userEntity = \App\Models\UserEntity::findOrFail($userEntityId);
        $user = Auth::user();

        if (!$user || !$user->moysklad_token) {
            session()->flash('error', 'МойСклад token not configured.');
            $this->closeRetryModal();
            return;
        }

        try {
            /** @var string $token */
            $token = $user->moysklad_token;
            $moySkladService = new \App\Services\MoySkladService($token);

            $webhookUrl = route('webhook.moysklad.entity', ['user_entity' => $userEntity->id], absolute: true);
            $result = $moySkladService->createWebhook($userEntity->entity->type, $webhookUrl, $userEntity->action);

            // Store response to display in modal
            $this->retryResponse = $result ? [
                'success' => isset($result['id']),
                'data' => $result['response'] ?? $result ?? [],
            ] : [
                'success' => false,
                'data' => ['error' => 'No response from МойСклад'],
            ];

            if (!$result || !isset($result['id'])) {
                session()->flash('error', "Failed to create {$userEntity->action} webhook");
                return;
            }

            $userEntity->update([
                'ms_id' => $result['id'],
                'messages' => json_encode([
                    'request_body' => $result['request_body'] ?? null,
                    'response' => $result['response'] ?? $result,
                ]),
            ]);

            session()->flash('success', "{$userEntity->action} webhook created successfully!");
        } catch (\Exception $e) {
            $this->retryResponse = [
                'success' => false,
                'data' => ['error' => $e->getMessage()],
            ];
            session()->flash('error', "Retry failed: " . $e->getMessage());
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

        $errors = [];

        // Create user_entity records and webhooks for each action type
        foreach (['CREATE', 'UPDATE', 'DELETE'] as $action) {
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
                    $errorMsg = "Failed to create {$action} webhook";
                    $userEntity->update([
                        'messages' => json_encode([
                            'error' => $errorMsg,
                            'failed_at' => now()->toDateTimeString(),
                        ]),
                    ]);
                    $errors[] = $errorMsg;
                    continue;
                }

                // 4. Update user_entity record with webhook ID
                $userEntity->update([
                    'ms_id' => $result['id'],
                    'messages' => json_encode([
                        'request_body' => $result['request_body'] ?? null,
                        'response' => $result['response'] ?? $result,
                    ]),
                ]);
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                $userEntity->update([
                    'messages' => json_encode([
                        'error' => $errorMsg,
                        'failed_at' => now()->toDateTimeString(),
                    ]),
                ]);
                $errors[] = "{$action}: " . $errorMsg;
                continue;
            }
        }

        if (!empty($errors)) {
            throw new \Exception("Some webhooks failed: " . implode(", ", $errors));
        }
    }

    public function testConnection(): void
    {
        $this->validate([
            'bearer_token' => 'required|string|min:10',
        ]);

        $this->testing = true;
        $this->test_passed = false;

        try {
            $service = new \App\Services\MoySkladService($this->bearer_token);
            $result = $service->testConnection();

            if ($result['success'] ?? false) {
                $this->test_message = '✓ Connection successful!';
                $this->test_passed = true;
            } else {
                $this->test_message = '✗ Invalid token. HTTP ' . ($result['status_code'] ?? 0);
                $this->test_passed = false;
            }
        } catch (\Exception $e) {
            $this->test_message = '✗ Connection failed: ' . $e->getMessage();
            $this->test_passed = false;
        } finally {
            $this->testing = false;
        }
    }

    public function saveToken(): void
    {
        if (!$this->test_passed) {
            $this->addError('test', 'Please test connection first');
            return;
        }

        $this->validate([
            'bearer_token' => 'required|string|min:10',
        ]);

        $user = Auth::user();
        $user->update([
            'moysklad_token' => $this->bearer_token,
        ]);

        session()->flash('success', 'МойСклад token saved!');
        $this->bearer_token = '';
        $this->test_message = null;
        $this->test_passed = false;
    }

    public function render()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $hasToken = $user && !empty($user->moysklad_token);

        return view('livewire.admin.entities', [
            'hasToken' => $hasToken,
            'allEntities' => $this->allEntities,
            'userEntities' => $this->userEntities,
        ]);
    }
}

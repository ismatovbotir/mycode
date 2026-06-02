<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Bot;
use App\Models\Integration;
use App\Models\IntegrationField;
use App\Services\MoySkladService;
use Livewire\Component;

class CreateIntegrationModal extends Component
{
    public Bot $bot;
    public bool $isOpen = false;
    public string $type = 'moisklad';
    public array $credentials = [];
    public bool $testing = false;
    public ?string $test_message = null;

    public function openModal()
    {
        $this->isOpen = true;
        $this->loadFields();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function loadFields()
    {
        $fields = IntegrationField::where('integration_type', $this->type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($fields as $field) {
            $this->credentials[$field->field_key] = '';
        }
    }

    public function testConnection()
    {
        $requiredFields = IntegrationField::where('integration_type', $this->type)
            ->where('is_required', true)
            ->pluck('field_key')
            ->toArray();

        $this->validate([
            'credentials' => 'required|array',
            ...$this->buildValidationRules(),
        ]);

        $this->testing = true;

        try {
            $service = new MoySkladService($this->credentials['api_token'] ?? '');
            if ($service->testConnection()) {
                $this->test_message = '✓ Connection successful!';
            } else {
                $this->test_message = '✗ Invalid token or API error';
            }
        } catch (\Exception $e) {
            $this->test_message = '✗ Connection failed: ' . $e->getMessage();
        } finally {
            $this->testing = false;
        }
    }

    public function save()
    {
        abort_if(!auth()->user()->can('update', $this->bot), 403);

        $this->validate([
            'type' => 'required|in:moisklad',
            'credentials' => 'required|array',
            ...$this->buildValidationRules(),
        ]);

        Integration::create([
            'bot_id' => $this->bot->id,
            'type' => $this->type,
            'credentials' => $this->credentials,
            'is_active' => true,
        ]);

        $this->dispatch('integration-created');
        $this->closeModal();
        session()->flash('success', 'МойСклад integration added successfully!');
    }

    private function buildValidationRules(): array
    {
        $rules = [];
        $fields = IntegrationField::where('integration_type', $this->type)->get();

        foreach ($fields as $field) {
            $rule = [];
            if ($field->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }
            $rule[] = 'string';
            $rules["credentials.{$field->field_key}"] = implode('|', $rule);
        }

        return $rules;
    }

    private function resetForm()
    {
        $this->type = 'moisklad';
        $this->credentials = [];
        $this->test_message = null;
    }

    public function render()
    {
        $fields = IntegrationField::where('integration_type', $this->type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.create-integration-modal', [
            'fields' => $fields,
        ]);
    }
}

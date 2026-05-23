<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Models\Integration;
use App\Services\MoySkladService;
use Livewire\Component;

class CreateIntegrationModal extends Component
{
    public Bot $bot;
    public bool $isOpen = false;
    public string $type = 'moisklad';
    public string $moisklad_token = '';
    public bool $testing = false;
    public ?string $test_message = null;

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function testConnection()
    {
        $this->validate([
            'moisklad_token' => 'required|string|min:10',
        ]);

        $this->testing = true;

        try {
            $service = new MoySkladService($this->moisklad_token);
            if ($service->testConnection()) {
                $this->test_message = '✓ Connection successful!';
                session()->flash('success', 'МойСклад token is valid!');
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
            'moisklad_token' => 'required|string|min:10',
        ]);

        Integration::create([
            'bot_id' => $this->bot->id,
            'type' => $this->type,
            'credentials' => [
                'api_token' => encrypt($this->moisklad_token),
            ],
            'settings' => [],
            'is_active' => true,
        ]);

        $this->dispatch('integration-created');
        $this->closeModal();
        session()->flash('success', 'МойСклад integration added successfully!');
    }

    private function resetForm()
    {
        $this->type = 'moisklad';
        $this->moisklad_token = '';
        $this->test_message = null;
    }

    public function render()
    {
        return view('livewire.create-integration-modal');
    }
}

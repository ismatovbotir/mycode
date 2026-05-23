<?php

namespace App\Livewire;

use App\Models\Integration;
use App\Services\MoySkladService;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateIntegrationModal extends Component
{
    public bool $isOpen = false;
    public string $type = 'moisklad';
    public string $moisklad_login = '';
    public string $moisklad_password = '';
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
            'moisklad_login' => 'required|string|email',
            'moisklad_password' => 'required|string|min:1',
        ]);

        $this->testing = true;

        try {
            $service = new MoySkladService($this->moisklad_login, $this->moisklad_password);
            if ($service->testConnection()) {
                $this->test_message = '✓ Connection successful!';
                session()->flash('success', 'MoySklad credentials are valid!');
            } else {
                $this->test_message = '✗ Invalid credentials or API error';
            }
        } catch (\Exception $e) {
            $this->test_message = '✗ Connection failed: ' . $e->getMessage();
        } finally {
            $this->testing = false;
        }
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:moisklad',
            'moisklad_login' => 'required|string|email',
            'moisklad_password' => 'required|string|min:1',
        ]);

        Integration::create([
            'uuid' => Str::uuid(),
            'company_id' => auth()->user()->company_id,
            'type' => $this->type,
            'credentials' => [
                'login' => encrypt($this->moisklad_login),
                'password' => encrypt($this->moisklad_password),
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
        $this->moisklad_login = '';
        $this->moisklad_password = '';
        $this->test_message = null;
    }

    public function render()
    {
        return view('livewire.create-integration-modal');
    }
}

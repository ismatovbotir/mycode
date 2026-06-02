<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\MoySkladService;
use Livewire\Component;

class MoyskladSettings extends Component
{
    public string $bearer_token = '';
    public bool $testing = false;
    public ?string $test_message = null;
    public bool $test_passed = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->bearer_token = $user->moysklad_token ?? '';
    }

    public function testConnection(): void
    {
        $this->validate([
            'bearer_token' => 'required|string|min:10',
        ]);

        $this->testing = true;
        $this->test_passed = false;

        try {
            $service = new MoySkladService($this->bearer_token);
            if ($service->testConnection()) {
                $this->test_message = '✓ Connection successful! Bearer token is valid.';
                $this->test_passed = true;
            } else {
                $this->test_message = '✗ Invalid bearer token. Please check your token.';
                $this->test_passed = false;
            }
        } catch (\Exception $e) {
            $this->test_message = '✗ Connection failed: ' . $e->getMessage();
            $this->test_passed = false;
        } finally {
            $this->testing = false;
        }
    }

    public function save(): void
    {
        if (!$this->test_passed) {
            $this->addError('test', 'Please test connection first before saving');
            return;
        }

        $this->validate([
            'bearer_token' => 'required|string|min:10',
        ]);

        $user = auth()->user();
        $user->update([
            'moysklad_token' => $this->bearer_token,
        ]);

        session()->flash('success', 'МойСклад bearer token saved successfully!');
        $this->test_message = null;
        $this->test_passed = false;
    }

    public function render()
    {
        return view('livewire.moysklad-settings');
    }
}

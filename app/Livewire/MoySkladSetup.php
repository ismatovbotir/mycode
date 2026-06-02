<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\MoySkladService;
use Livewire\Component;

class MoySkladSetup extends Component
{
    public string $bearer_token = '';
    public bool $testing = false;
    public ?string $test_message = null;
    public bool $test_passed = false;
    public bool $showModal = false;
    public ?string $requestMethod = null;
    public ?string $requestUrl = null;
    public array $requestHeaders = [];
    public ?int $responseCode = null;
    public ?string $responseBody = null;

    public function mount(): void
    {
        //
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
            $result = $service->testConnection();
            $this->requestMethod = $result['method'] ?? 'GET';
            $this->requestUrl = $result['url'] ?? 'https://api.moysklad.ru/api/remap/1.2/context';
            $this->requestHeaders = $result['headers'] ?? [];
            $this->responseCode = $result['status_code'] ?? 0;
            $this->responseBody = is_array($result['body'] ?? null)
                ? json_encode($result['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                : ($result['body'] ?? 'No response body');

            if ($result['success'] ?? false) {
                $this->test_message = '✓ Connection successful! Bearer token is valid.';
                $this->test_passed = true;
            } else {
                $this->test_message = '✗ Invalid bearer token (HTTP ' . $this->responseCode . '). Please check and try again.';
                $this->test_passed = false;
            }
        } catch (\Exception $e) {
            $this->requestMethod = 'GET';
            $this->requestUrl = 'https://api.moysklad.ru/api/remap/1.2/context';
            $this->requestHeaders = [];
            $this->responseCode = 0;
            $this->responseBody = 'Error: ' . $e->getMessage();
            $this->test_message = '✗ Connection failed: ' . $e->getMessage();
            $this->test_passed = false;
        } finally {
            $this->testing = false;
            $this->showModal = true;
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        if (!$this->test_passed) {
            $this->addError('test', 'Please test connection first');
            return;
        }

        $this->validate([
            'bearer_token' => 'required|string|min:10',
        ]);

        $user = auth()->user();
        $user->update([
            'moysklad_token' => $this->bearer_token,
        ]);

        session()->flash('success', 'МойСклад token saved! Redirecting to dashboard...');

        $this->redirect(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.moysklad-setup');
    }
}

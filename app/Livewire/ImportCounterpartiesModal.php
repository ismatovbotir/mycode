<?php
// app/Livewire/ImportCounterpartiesModal.php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Client;
use App\Models\Integration;
use App\Services\MoySkladService;
use Livewire\Component;

class ImportCounterpartiesModal extends Component
{
    public bool $isOpen = false;
    public bool $isImporting = false;
    public ?string $status = null;
    public int $imported = 0;
    public int $total = 0;

    public function mount()
    {
        // Reset state
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetImport();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetImport();
    }

    public function import()
    {
        $this->isImporting = true;
        $this->imported = 0;
        $this->total = 0;

        try {
            $bot = auth()->user()->bot;
            abort_if(!$bot, 404);

            $integration = $bot->integrations()->where('type', 'moysklad')->first();
            abort_if(!$integration, 404);

            $token = decrypt($integration->credentials['api_token']);
            $service = new MoySkladService($token);

            $this->status = 'Fetching counterparties from МойСклад...';

            $counterparties = $service->getAllCounterparties();

            if (!$counterparties) {
                $this->status = '❌ Failed to fetch counterparties';
                $this->isImporting = false;
                return;
            }

            $this->total = count($counterparties);
            $this->status = "Found {$this->total} counterparties. Importing...";

            foreach ($counterparties as $counterparty) {
                try {
                    $this->importCounterparty($bot->id, $counterparty);
                    $this->imported++;
                } catch (\Exception $e) {
                    \Log::error('Failed to import counterparty', [
                        'counterparty_id' => $counterparty['id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->status = "✓ Successfully imported {$this->imported}/{$this->total} counterparties";
            session()->flash('success', "Imported {$this->imported} counterparties from МойСклад");

            $this->dispatch('counterparties-imported');
        } catch (\Exception $e) {
            $this->status = '❌ Error: ' . $e->getMessage();
            \Log::error('Counterparty import failed', ['error' => $e->getMessage()]);
        } finally {
            $this->isImporting = false;
        }
    }

    private function importCounterparty(string $botId, array $counterparty): void
    {
        $inn = $counterparty['inn'] ?? null;
        $phone = null;

        // Extract phone from phone array if present
        if (!empty($counterparty['phones']) && is_array($counterparty['phones'])) {
            $phone = $counterparty['phones'][0]['number'] ?? null;
        }

        Client::updateOrCreate(
            [
                'bot_id' => $botId,
                'moisklad_id' => $counterparty['id'],
            ],
            [
                'name' => $counterparty['name'] ?? 'Unknown',
                'type' => $counterparty['isUser'] ? 'entity' : 'individual',
                'phone' => $phone,
                'email' => $counterparty['email'] ?? null,
                'inn' => $inn,
                'address' => $counterparty['actualAddress'] ?? null,
                'metadata' => [
                    'raw_data' => $counterparty,
                    'imported_at' => now()->toIso8601String(),
                ],
            ]
        );
    }

    private function resetImport()
    {
        $this->isImporting = false;
        $this->status = null;
        $this->imported = 0;
        $this->total = 0;
    }

    public function render()
    {
        return view('livewire.import-counterparties-modal');
    }
}

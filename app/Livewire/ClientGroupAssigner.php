<?php

namespace App\Livewire;

use App\Models\BotClient;
use App\Models\ClientGroup;
use Livewire\Component;

class ClientGroupAssigner extends Component
{
    public BotClient $client;
    public $selectedGroups = [];
    public $availableGroups = [];

    public function mount()
    {
        $this->loadAvailableGroups();
        $this->loadSelectedGroups();
    }

    public function loadAvailableGroups()
    {
        $this->availableGroups = ClientGroup::where('bot_id', $this->client->bot_id)
            ->get()
            ->map(fn($g) => ['id' => $g->id, 'name' => $g->name])
            ->toArray();
    }

    public function loadSelectedGroups()
    {
        $this->selectedGroups = $this->client->groups()
            ->pluck('id')
            ->toArray();
    }

    public function updateGroups()
    {
        $this->client->groups()->sync($this->selectedGroups);
        session()->flash('success', 'Client groups updated!');
    }

    public function render()
    {
        return view('livewire.client-group-assigner');
    }
}

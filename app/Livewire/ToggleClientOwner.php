<?php
// app/Livewire/ToggleClientOwner.php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\BotClient;
use Livewire\Component;

class ToggleClientOwner extends Component
{
    public BotClient $client;
    public bool $isOwner;

    public function mount()
    {
        $this->isOwner = $this->client->is_owner;
    }

    public function toggle()
    {
        $this->authorize('update', $this->client->bot);

        $this->client->update([
            'is_owner' => !$this->isOwner,
        ]);

        $this->isOwner = !$this->isOwner;

        session()->flash('success', $this->isOwner
            ? 'Client set as report owner'
            : 'Client removed from report owners'
        );
    }

    public function render()
    {
        return view('livewire.toggle-client-owner');
    }
}

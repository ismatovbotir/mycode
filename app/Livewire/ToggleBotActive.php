<?php

namespace App\Livewire;

use App\Models\Bot;
use Livewire\Component;

class ToggleBotActive extends Component
{
    public Bot $bot;
    public bool $loading = false;

    public function toggle()
    {
        abort_if(!auth()->user()->can('update', $this->bot), 403);
        $this->loading = true;

        try {
            $this->bot->update(['is_active' => !$this->bot->is_active]);
            session()->flash('success', $this->bot->is_active ? 'Bot activated!' : 'Bot deactivated!');
        } catch (\Exception $e) {
            \Log::error('Failed to toggle bot', ['bot_id' => $this->bot->id, 'error' => $e->getMessage()]);
            session()->flash('error', 'Failed to toggle bot status');
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.toggle-bot-active');
    }
}

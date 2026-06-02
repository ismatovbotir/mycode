<?php

namespace App\Livewire;

use App\Models\ClientGroup;
use Illuminate\Support\Str;
use Livewire\Component;

class ClientGroupManager extends Component
{
    public $groups = [];
    public $showCreateModal = false;
    public $newGroupName = '';

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $bot = auth()->user()->bot;
        if (!$bot) {
            $this->groups = [];
            return;
        }
        $this->groups = $bot->groups()->get()->toArray();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->newGroupName = '';
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->newGroupName = '';
    }

    public function createGroup()
    {
        $bot = auth()->user()->bot;
        abort_if(!$bot, 404);

        $this->validate([
            'newGroupName' => ['required', 'string', 'max:255'],
        ]);

        ClientGroup::create([
            'id' => Str::uuid(),
            'bot_id' => $bot->id,
            'name' => $this->newGroupName,
        ]);

        $this->closeCreateModal();
        $this->loadGroups();
        session()->flash('success', 'Group created successfully!');
    }

    public function deleteGroup($groupId)
    {
        $bot = auth()->user()->bot;
        abort_if(!$bot, 404);

        $group = ClientGroup::findOrFail($groupId);
        abort_if($group->bot_id !== $bot->id, 403);
        $group->delete();

        $this->loadGroups();
        session()->flash('success', 'Group deleted!');
    }

    public function render()
    {
        return view('livewire.client-group-manager');
    }
}

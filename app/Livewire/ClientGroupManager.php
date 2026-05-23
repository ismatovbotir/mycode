<?php

namespace App\Livewire;

use App\Models\ClientGroup;
use Illuminate\Support\Str;
use Livewire\Component;

class ClientGroupManager extends Component
{
    public $company;
    public $groups = [];
    public $showCreateModal = false;
    public $newGroupName = '';
    public $selectedBot = null;

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $this->groups = $this->company->groups()->with('bot')->get()->toArray();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->newGroupName = '';
        $this->selectedBot = null;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->newGroupName = '';
        $this->selectedBot = null;
    }

    public function createGroup()
    {
        $this->validate([
            'newGroupName' => ['required', 'string', 'max:255'],
            'selectedBot' => ['required', 'uuid'],
        ]);

        $bot = $this->company->bots()->findOrFail($this->selectedBot);

        ClientGroup::create([
            'id' => Str::uuid(),
            'company_id' => $this->company->id,
            'bot_id' => $bot->id,
            'name' => $this->newGroupName,
        ]);

        $this->closeCreateModal();
        $this->loadGroups();
        session()->flash('success', 'Group created successfully!');
    }

    public function deleteGroup($groupId)
    {
        $group = ClientGroup::findOrFail($groupId);
        abort_if($group->company_id !== $this->company->id, 403);
        $group->delete();

        $this->loadGroups();
        session()->flash('success', 'Group deleted!');
    }

    public function render()
    {
        return view('livewire.client-group-manager');
    }
}

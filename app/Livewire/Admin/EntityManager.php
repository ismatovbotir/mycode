<?php
// app/Livewire/Admin/EntityManager.php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Entity;
use Livewire\Component;

class EntityManager extends Component
{
    public function render()
    {
        $entities = Entity::orderBy('type')->get();
        return view('livewire.admin.entity-manager', compact('entities'));
    }

    public function toggleActive(int $entityId): void
    {
        $entity = Entity::findOrFail($entityId);
        $entity->update(['is_active' => !$entity->is_active]);

        $status = $entity->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Entity '{$entity->type}' {$status}");
    }
}

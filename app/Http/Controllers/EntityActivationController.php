<?php
// app/Http/Controllers/EntityActivationController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\UserEntity;
use App\Models\Entity;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntityActivationController
{
    public function index(): View
    {
        $user = auth()->user();

        // Get all available entities
        $allEntities = Entity::orderBy('type')->get();

        // Get user's activated entities
        $activatedEntityIds = UserEntity::where('user_id', $user->id)
            ->pluck('entity_id')
            ->toArray();

        return view('entities.activation', compact('allEntities', 'activatedEntityIds'));
    }

    public function activate(Entity $entity): RedirectResponse
    {
        $user = auth()->user();

        UserEntity::firstOrCreate(
            [
                'user_id' => $user->id,
                'entity_id' => $entity->id,
            ],
            [
                'ms_id' => null,
            ]
        );

        return redirect()
            ->route('entities.activation')
            ->with('success', "Entity '{$entity->type}' activated successfully");
    }

    public function deactivate(Entity $entity): RedirectResponse
    {
        $user = auth()->user();

        UserEntity::where('user_id', $user->id)
            ->where('entity_id', $entity->id)
            ->delete();

        return redirect()
            ->route('entities.activation')
            ->with('success', "Entity '{$entity->type}' deactivated successfully");
    }
}

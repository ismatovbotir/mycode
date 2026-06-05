<?php
// app/Http/Controllers/Admin/EntityController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Entity;
use Illuminate\View\View;

class EntityController
{
    public function index(): View
    {
        $entities = Entity::orderBy('type')->get();
        return view('admin.entities.index', compact('entities'));
    }

    public function show(Entity $entity): View
    {
        return view('admin.entities.show', compact('entity'));
    }

    public function edit(Entity $entity): View
    {
        return view('admin.entities.edit', compact('entity'));
    }

    public function update(Entity $entity)
    {
        $validated = request()->validate([
            'is_active' => 'boolean',
            'is_document' => 'boolean',
            'document_format' => 'nullable|array',
            'translations' => 'array',
            'messages' => 'array',
        ]);

        $entity->update($validated);

        return redirect()->route('admin.entities.show', $entity)
            ->with('success', 'Entity updated successfully!');
    }
}

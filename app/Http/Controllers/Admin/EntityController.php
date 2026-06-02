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
}

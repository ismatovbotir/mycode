<?php
// app/Models/Entity.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['type', 'is_active', 'translations', 'messages'])]
class Entity extends Model
{
    public $timestamps = false;

    protected $casts = [
        'is_active' => 'boolean',
        'translations' => 'array',
        'messages' => 'array',
    ];
}

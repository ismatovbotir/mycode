<?php
// app/Models/Entity.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'is_active', 'is_document', 'document_format', 'translations', 'messages'])]
class Entity extends Model
{
    public $timestamps = false;

    protected $casts = [
        'is_active' => 'boolean',
        'is_document' => 'boolean',
        'document_format' => 'array',
        'translations' => 'array',
        'messages' => 'array',
    ];

    public function userEntities(): HasMany
    {
        return $this->hasMany(UserEntity::class, 'entity_id', 'id');
    }
}

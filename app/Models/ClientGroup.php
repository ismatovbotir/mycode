<?php
// app/Models/ClientGroup.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['uuid', 'bot_id', 'name'])]
class ClientGroup extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ClientGroupMember::class, 'group_id');
    }
}

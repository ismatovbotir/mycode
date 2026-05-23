<?php
// app/Models/Broadcast.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['bot_id', 'group_id', 'message', 'scheduled_at', 'status'])]
class Broadcast extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(ClientGroup::class, 'group_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'broadcast_id');
    }
}

<?php
// app/Models/WebhookEventType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['event_type', 'name', 'description', 'icon', 'fields', 'is_active'])]
class WebhookEventType extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function bots(): BelongsToMany
    {
        return $this->belongsToMany(Bot::class, 'bot_webhook_event_types')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'tg_bot_token', 'tg_bot_id', 'tg_first_name', 'tg_username', 'tg_bot_metadata', 'webhook_status', 'content', 'is_active', 'requires_admin_approval'])]
class Bot extends Model
{
    use HasFactory, HasUuids;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected $casts = [
        'content' => 'array',
        'tg_bot_metadata' => 'array',
        'is_active' => 'boolean',
        'requires_admin_approval' => 'boolean',
        'tg_bot_token' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(BotClient::class);
    }

    public function eventTemplates(): HasMany
    {
        return $this->hasMany(BotEventTemplate::class);
    }

    public function webhookEvents(): HasMany
    {
        return $this->hasMany(WebhookEvent::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function broadcasts(): HasMany
    {
        return $this->hasMany(Broadcast::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ClientGroup::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class);
    }

    public function moiskladClients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function webhookEventTypes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(WebhookEventType::class, 'bot_webhook_event_types')
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}


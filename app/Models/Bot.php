<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['uuid', 'company_id', 'name', 'tg_bot_token', 'webhook_secret', 'content', 'is_active', 'requires_admin_approval'])]
class Bot extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
        'requires_admin_approval' => 'boolean',
        'tg_bot_token' => 'encrypted',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
}

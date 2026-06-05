<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookEvent extends Model
{
    protected $fillable = [
        'bot_id',
        'user_entity_id',
        'event_type',
        'payload',
        'status',
        'matched',
        'bot_client_id',
        'tg_status',
        'sent_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'matched' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function userEntity(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_entity_id', 'id');
    }

    public function botClient(): BelongsTo
    {
        return $this->belongsTo(BotClient::class, 'bot_client_id', 'id');
    }
}

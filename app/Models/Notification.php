<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['bot_id', 'bot_client_id', 'broadcast_id', 'message', 'tg_status', 'sent_at'])]
class Notification extends Model
{
    use HasFactory, HasUuids;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botClient(): BelongsTo
    {
        return $this->belongsTo(BotClient::class);
    }

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(Broadcast::class);
    }
}

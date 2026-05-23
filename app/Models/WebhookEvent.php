<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['uuid', 'bot_id', 'event_type', 'payload', 'status', 'client_id'])]
class WebhookEvent extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $casts = [
        'payload' => 'array',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(BotClient::class, 'client_id');
    }
}

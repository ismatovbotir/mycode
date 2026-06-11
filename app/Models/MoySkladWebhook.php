<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['webhook_id', 'user_entity_id', 'bot_id', 'event_type', 'entity_type', 'document_url', 'document_id', 'payload', 'status', 'matched_client_id', 'error_message'])]
class MoySkladWebhook extends Model
{
    use HasFactory;

    protected $table = 'moysklad_webhooks';

    protected $casts = [
        'payload' => 'array',
    ];

    public function userEntity(): BelongsTo
    {
        return $this->belongsTo(UserEntity::class, 'user_entity_id', 'id');
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function matchedClient(): BelongsTo
    {
        return $this->belongsTo(BotClient::class, 'matched_client_id', 'id');
    }

    public function markProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markProcessed(): void
    {
        $this->update(['status' => 'processed']);
    }

    public function markFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}

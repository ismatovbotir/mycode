<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['message_id', 'bot_id', 'tg_user_id', 'message', 'message_type', 'raw_update'])]
class TgUserMessage extends Model
{
    use HasFactory;

    protected $table = 'tg_user_messages';

    protected $casts = [
        'raw_update' => 'array',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function tgUser(): BelongsTo
    {
        return $this->belongsTo(TgUser::class, 'tg_user_id', 'id');
    }
}

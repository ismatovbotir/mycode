<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['bot_id', 'tg_user_id', 'mySklad_id', 'matched', 'matched_at', 'approved', 'approved_at'])]
class BotClient extends Model
{
    use HasFactory, HasUuids;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    protected $casts = [
        'matched' => 'boolean',
        'matched_at' => 'datetime',
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function tgUser(): BelongsTo
    {
        return $this->belongsTo(TgUser::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(ClientGroup::class, 'client_group_members', 'bot_client_id', 'group_id');
    }
}

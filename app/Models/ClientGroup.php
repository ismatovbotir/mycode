<?php
// app/Models/ClientGroup.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['company_id', 'bot_id', 'name'])]
class ClientGroup extends Model
{
    use HasFactory, HasUuids;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(ClientGroupMember::class, 'group_id');
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(BotClient::class, 'client_group_members', 'group_id', 'bot_client_id');
    }
}

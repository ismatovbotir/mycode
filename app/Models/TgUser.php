<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['uuid', 'chat_id', 'phone', 'first_name', 'last_name', 'username', 'lang'])]
class TgUser extends Model
{
    use HasFactory, HasUuids;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function botClients(): HasMany
    {
        return $this->hasMany(BotClient::class);
    }
}

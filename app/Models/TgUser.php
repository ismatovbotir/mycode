<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id', 'phone', 'first_name', 'last_name', 'username', 'lang'])]
class TgUser extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function botClients(): HasMany
    {
        return $this->hasMany(BotClient::class, 'tg_user_id', 'id');
    }
}

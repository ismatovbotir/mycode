<?php
// app/Models/ClientGroupMember.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['group_id', 'bot_client_id'])]
class ClientGroupMember extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function group(): BelongsTo
    {
        return $this->belongsTo(ClientGroup::class, 'group_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(BotClient::class, 'bot_client_id');
    }
}

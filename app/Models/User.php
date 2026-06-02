<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'brand_name', 'email', 'password', 'lang', 'role', 'phone', 'tg_chat_id', 'tg_linked_at', 'moysklad_token'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tg_linked_at' => 'datetime',
        'moysklad_token' => 'encrypted',
    ];

    public function bot(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Bot::class);
    }

    public function entities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserEntity::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}

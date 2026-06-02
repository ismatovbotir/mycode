<?php

namespace App\Providers;

use App\Models\Bot;
use App\Policies\BotPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Bot::class => BotPolicy::class,
    ];

    public function boot(): void {}
}

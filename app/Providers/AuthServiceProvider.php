<?php

namespace App\Providers;

use App\Models\Bot;
use App\Models\Company;
use App\Policies\BotPolicy;
use App\Policies\CompanyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Bot::class => BotPolicy::class,
        Company::class => CompanyPolicy::class,
    ];

    public function boot(): void {}
}

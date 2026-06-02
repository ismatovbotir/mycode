<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin from environment variables
        User::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'admin@mycode.uz')],
            [
                'id' => Str::uuid(),
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'brand_name' => env('SUPER_ADMIN_BRAND_NAME', 'MyCode'),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'password')),
                'phone' => env('SUPER_ADMIN_PHONE'),
                'lang' => 'ru',
                'role' => 'super_admin',
            ]
        );

        // Create Test User
        User::firstOrCreate(
            ['email' => 'user@mycode.uz'],
            [
                'id' => Str::uuid(),
                'name' => 'Test User',
                'brand_name' => 'Test Brand',
                'password' => Hash::make('password'),
                'lang' => 'ru',
                'role' => 'admin',
            ]
        );

        $this->call([
            IntegrationFieldSeeder::class,
            WebhookEventTypeSeeder::class,
            EntitySeeder::class,
        ]);
    }
}

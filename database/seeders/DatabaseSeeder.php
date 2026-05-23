<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company = \App\Models\Company::create([
            'name' => 'Test Company',
            'inn' => '123456789',
            'email' => 'company@example.com',
        ]);

        User::create([
            'company_id' => $company->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}

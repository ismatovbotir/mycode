<?php
// database/migrations/2026_01_01_000004_create_tg_users_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tg_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->bigInteger('chat_id')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('lang', 5)->default('uz');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tg_users');
    }
};

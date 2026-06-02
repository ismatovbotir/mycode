<?php
// database/migrations/2026_01_01_000001_create_users_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('brand_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('lang', 5)->default('uz');
            $table->enum('role', ['admin', 'super_admin'])->default('admin');
            $table->bigInteger('tg_chat_id')->nullable()->unique();
            $table->timestamp('tg_linked_at')->nullable();
            $table->text('moysklad_token')->unique()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

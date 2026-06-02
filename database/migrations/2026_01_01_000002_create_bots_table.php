<?php
// database/migrations/2026_01_01_000002_create_bots_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('tg_bot_token');
            $table->enum('webhook_status', ['pending', 'success', 'failed'])->default('pending');
            $table->uuid('webhook_secret');
            $table->json('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_admin_approval')->default(false);
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};

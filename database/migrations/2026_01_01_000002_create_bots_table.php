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
            $table->bigInteger('tg_bot_id')->nullable()->unique();
            $table->string('tg_first_name')->nullable();
            $table->string('tg_username')->nullable()->unique();
            $table->json('tg_bot_metadata')->nullable();
            $table->boolean('webhook_status')->default(false);
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

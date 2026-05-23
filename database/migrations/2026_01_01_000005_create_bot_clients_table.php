<?php
// database/migrations/2026_01_01_000005_create_bot_clients_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bot_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->foreignId('bot_id')->constrained('bots')->onDelete('cascade');
            $table->foreignId('tg_user_id')->constrained('tg_users')->onDelete('cascade');
            $table->string('mySklad_id')->nullable();
            $table->boolean('matched')->default(false);
            $table->timestamp('matched_at')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->unique(['bot_id', 'tg_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_clients');
    }
};

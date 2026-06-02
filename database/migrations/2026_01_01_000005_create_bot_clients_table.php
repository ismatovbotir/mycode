<?php
// database/migrations/2026_01_01_000005_create_bot_clients_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bot_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->string('tg_user_id');
            $table->foreign('tg_user_id')->references('id')->on('tg_users')->onDelete('cascade');
            $table->foreignUuid('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->boolean('approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_owner')->default(false);
            $table->timestamps();
            $table->unique(['bot_id', 'tg_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_clients');
    }
};

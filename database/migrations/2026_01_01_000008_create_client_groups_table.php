<?php
// database/migrations/2026_01_01_000008_create_client_groups_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->unique(['bot_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_groups');
    }
};

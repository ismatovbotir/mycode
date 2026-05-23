<?php
// database/migrations/2026_01_01_000009_create_client_group_members_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('client_groups')->onDelete('cascade');
            $table->foreignId('bot_client_id')->constrained('bot_clients')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['group_id', 'bot_client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_group_members');
    }
};

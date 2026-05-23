<?php
// database/migrations/2026_01_01_000010_create_broadcasts_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->foreignUuid('group_id')->nullable()->constrained('client_groups')->onDelete('set null');
            $table->text('message');
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcasts');
    }
};

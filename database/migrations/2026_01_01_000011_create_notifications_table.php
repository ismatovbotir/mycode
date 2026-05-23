<?php
// database/migrations/2026_01_01_000011_create_notifications_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->default(DB::raw('(UUID())'));
            $table->foreignId('bot_id')->constrained('bots')->onDelete('cascade');
            $table->foreignId('bot_client_id')->constrained('bot_clients')->onDelete('cascade');
            $table->foreignId('broadcast_id')->nullable()->constrained('broadcasts')->onDelete('set null');
            $table->text('message');
            $table->enum('tg_status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

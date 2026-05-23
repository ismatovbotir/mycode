<?php
// database/migrations/2026_01_01_000007_create_webhook_events_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->string('event_type');
            $table->json('payload');
            $table->enum('status', ['pending', 'processing', 'sent', 'failed'])->default('pending');
            $table->foreignUuid('bot_client_id')->nullable()->constrained('bot_clients')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};

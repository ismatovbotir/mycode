<?php
// database/migrations/2026_01_01_000012_create_webhook_event_types_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('webhook_event_types', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('event_type')->unique(); // e.g., 'entity.counterparty.create'
            $table->string('name'); // e.g., 'Counterparty Created'
            $table->text('description')->nullable();
            $table->string('icon')->default('📌'); // emoji icon
            $table->json('fields')->nullable(); // expected fields in payload
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bot_webhook_event_types', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->foreignUuid('webhook_event_type_id')->constrained('webhook_event_types')->onDelete('cascade');
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
            $table->unique(['bot_id', 'webhook_event_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_webhook_event_types');
        Schema::dropIfExists('webhook_event_types');
    }
};

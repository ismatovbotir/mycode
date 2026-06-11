<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('moysklad_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('webhook_id')->unique()->index(); // MoySkład webhook ID
            $table->foreignUuid('user_entity_id')->nullable()->constrained('user_entities')->onDelete('set null');
            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->string('event_type'); // CREATE, UPDATE, DELETE
            $table->string('entity_type'); // demand, supply, invoice, etc.
            $table->string('document_url')->nullable(); // Full URL to document
            $table->string('document_id')->nullable()->index(); // MoySkład document ID
            $table->json('payload'); // Full webhook payload from MoySkład
            $table->enum('status', ['received', 'processing', 'processed', 'failed'])->default('received')->index();
            $table->foreignUuid('matched_client_id')->nullable()->constrained('bot_clients')->onDelete('set null');
            $table->string('error_message')->nullable(); // If status is failed
            $table->timestamps();

            // Composite index for finding webhooks for a bot/entity
            $table->index(['bot_id', 'entity_type']);
            $table->index(['bot_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moysklad_webhooks');
    }
};

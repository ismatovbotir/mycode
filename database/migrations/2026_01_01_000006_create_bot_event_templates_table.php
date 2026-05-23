<?php
// database/migrations/2026_01_01_000006_create_bot_event_templates_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bot_event_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained('bots')->onDelete('cascade');
            $table->enum('event_type', ['supply', 'demand', 'paymentin', 'paymentout', 'salesreturn']);
            $table->json('messages');
            $table->timestamps();
            $table->unique(['bot_id', 'event_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_event_templates');
    }
};

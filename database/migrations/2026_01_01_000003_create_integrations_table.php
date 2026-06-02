<?php
// database/migrations/2026_01_01_000003_create_integrations_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');

            $table->enum('type', ['moysklad', 'bitrix', '1c'])->default('moysklad');
            $table->text('credentials');
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['bot_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};

<?php
// database/migrations/2026_01_01_000013_create_integration_fields_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('integration_fields', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('integration_type'); // moysklad, bitrix, 1c
            $table->string('field_key'); // api_token, base_url, etc.
            $table->string('label'); // "API Token", "Base URL", etc.
            $table->enum('type', ['text', 'password', 'url', 'email', 'number', 'select'])->default('text');
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['integration_type', 'field_key']);
            $table->index('integration_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_fields');
    }
};

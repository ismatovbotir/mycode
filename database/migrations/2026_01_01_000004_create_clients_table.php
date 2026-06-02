<?php
// database/migrations/2026_01_01_000004_create_clients_table.php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('type')->default('entity'); // entity, individual
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('inn', 20)->nullable();
            $table->text('address')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

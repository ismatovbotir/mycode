<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_entities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entity_id')->constrained();
            $table->string('action')->nullable();
            $table->string('ms_id')->nullable();
            $table->json('messages')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'entity_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_entities');
    }
};

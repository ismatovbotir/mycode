<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_document')->default(false);
            $table->json('document_format')->nullable();
            $table->string('type')->unique();
            $table->json('translations')->default(json_encode(['uz' => '', 'en' => '', 'ru' => '']));
            $table->json('messages')->default(json_encode(['uz' => '', 'en' => '',  'ru' => '']));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS entities CASCADE');
    }
};

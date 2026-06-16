<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_entities', function (Blueprint $table) {
            $table->foreignUuid('bot_id')
                ->nullable()
                ->constrained('bots')
                ->onDelete('cascade')
                ->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_entities', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['bot_id']);
            $table->dropColumn('bot_id');
        });
    }
};

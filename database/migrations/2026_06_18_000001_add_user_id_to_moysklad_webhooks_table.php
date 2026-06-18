<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('moysklad_webhooks', function (Blueprint $table) {
            $table->foreignUuid('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->after('bot_id');
        });
    }

    public function down(): void
    {
        Schema::table('moysklad_webhooks', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};

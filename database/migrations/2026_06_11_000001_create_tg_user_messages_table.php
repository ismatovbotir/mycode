<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tg_user_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('message_id')->index();
            $table->foreignUuid('bot_id')->constrained('bots')->onDelete('cascade');
            $table->string('tg_user_id')->index();
            $table->foreign('tg_user_id')->references('id')->on('tg_users')->onDelete('cascade');
            $table->text('message');
            $table->string('message_type')->default('text'); // text, photo, document, etc.
            $table->json('raw_update')->nullable(); // Store full Telegram update for debugging
            $table->timestamps();

            // Composite index for finding user messages in a bot
            $table->index(['bot_id', 'tg_user_id']);
            // Unique message_id per bot (Telegram message IDs are unique per chat, not globally)
            $table->unique(['bot_id', 'message_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tg_user_messages');
    }
};

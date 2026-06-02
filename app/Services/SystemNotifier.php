<?php
// app/Services/SystemNotifier.php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class SystemNotifier
{
    public function __construct(private readonly TelegramService $telegram) {}

    public function notifyAdmin(string $message): void
    {
        $token = config('app.system_bot_token');
        $chatId = (int) config('app.superadmin_chat_id');

        if (!$token || !$chatId) {
            Log::warning('System bot not configured', ['message' => $message]);
            return;
        }

        try {
            $this->telegram->sendMessage($token, $chatId, $message);
        } catch (\Throwable $e) {
            Log::error('Failed to notify admin', ['error' => $e->getMessage()]);
        }
    }

    public function notifyUser(User $user, string $message): void
    {
        if (!$user->tg_chat_id) {
            return;
        }

        $token = config('app.system_bot_token');
        if (!$token) {
            Log::warning('System bot not configured');
            return;
        }

        try {
            $this->telegram->sendMessage($token, (int) $user->tg_chat_id, $message);
        } catch (\Throwable $e) {
            Log::error('Failed to notify user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

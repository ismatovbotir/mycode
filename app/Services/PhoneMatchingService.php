<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BotClient;
use App\Models\TgUser;

class PhoneMatchingService
{
    public function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    public function findBotClientByPhone(string $phone, string $botId): ?BotClient
    {
        $normalizedPhone = $this->normalizePhone($phone);

        if (empty($normalizedPhone)) {
            return null;
        }

        return BotClient::where('bot_id', $botId)
            ->whereHas('tgUser', function ($query) use ($normalizedPhone) {
                $query->where('phone', $normalizedPhone);
            })
            ->first();
    }

    public function findTgUserByPhone(string $phone): ?TgUser
    {
        $normalizedPhone = $this->normalizePhone($phone);

        if (empty($normalizedPhone)) {
            return null;
        }

        return TgUser::where('phone', $normalizedPhone)->first();
    }
}

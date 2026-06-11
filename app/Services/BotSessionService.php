<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class BotSessionService
{
    private const TTL = 86400; // 24 часа

    public function get(string $botId, string $chatId): ?array
    {
        $key = $this->getKey($botId, $chatId);
        $data = Redis::get($key);

        return $data ? json_decode($data, true) : null;
    }

    public function set(int $botId, int $chatId, array $data): void
    {
        $key = $this->getKey($botId, $chatId);
        Redis::setex($key, self::TTL, json_encode($data));
    }

    public function update(int $botId, int $chatId, array $data): void
    {
        $session = $this->get($botId, $chatId) ?? [];
        $merged = array_merge($session, $data);
        $this->set($botId, $chatId, $merged);
    }

    public function forget(int $botId, int $chatId): void
    {
        $key = $this->getKey($botId, $chatId);
        Redis::del($key);
    }

    private function getKey(int $botId, int $chatId): string
    {
        return "bot_session:{$botId}:{$chatId}";
    }
}

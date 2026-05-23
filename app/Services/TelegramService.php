<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $apiUrl = 'https://api.telegram.org/bot';

    public function setWebhook(string $token, string $url): bool
    {
        $response = Http::get("{$this->apiUrl}{$token}/setWebhook", [
            'url' => $url,
        ]);

        return $response->ok() && $response->json('ok') === true;
    }

    public function sendMessage(string $token, int $chatId, string $text, ?array $replyMarkup = null): array
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ];

        if ($replyMarkup) {
            $payload['reply_markup'] = $replyMarkup;
        }

        $response = Http::post("{$this->apiUrl}{$token}/sendMessage", $payload);

        return $response->json();
    }

    public function parseUpdate(array $update): array
    {
        if (isset($update['message'])) {
            return [
                'type' => 'message',
                'chat_id' => $update['message']['chat']['id'],
                'text' => $update['message']['text'] ?? null,
                'contact' => $update['message']['contact'] ?? null,
                'from' => $update['message']['from'],
            ];
        }

        return [];
    }

    public function requestContact(string $token, int $chatId, string $lang): void
    {
        $buttons = [
            'uz' => 'Telefon raqamni yuborish',
            'ru' => 'Отправить номер телефона',
            'tj' => 'Фиристани рақами телефон',
            'kk' => 'Телефон номерин жібергіз',
            'kz' => 'Телефон номерін жіберіңіз',
        ];

        $text = $buttons[$lang] ?? $buttons['ru'];

        $this->sendMessage($token, $chatId, $text, [
            'one_time_keyboard' => true,
            'keyboard' => [[
                ['text' => $text, 'request_contact' => true],
            ]],
        ]);
    }

    public function sendLanguageKeyboard(string $token, int $chatId): void
    {
        $this->sendMessage($token, $chatId, 'Tilni tanla / Выберите язык / Забони интихоб кун / Тілді таңдаңыз', [
            'one_time_keyboard' => true,
            'keyboard' => [
                [['text' => '🇺🇿 O\'zbek'], ['text' => '🇷🇺 Русский']],
                [['text' => '🇹🇯 Тоҷикӣ'], ['text' => '🇰🇿 Қазақша']],
                [['text' => '🏳️ Қарақалпақ']],
            ],
        ]);
    }

    public function deleteWebhook(string $token): bool
    {
        $response = Http::get("{$this->apiUrl}{$token}/deleteWebhook");

        return $response->ok() && $response->json('ok') === true;
    }
}

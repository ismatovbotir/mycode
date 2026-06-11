<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Bot;
use App\Models\BotClient;
use App\Models\TgUser;
use App\Services\BotSessionService;
use App\Services\DeveloperNotificationService;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController
{
    public function __construct(
        private TelegramService $telegramService,
        private BotSessionService $sessionService,
        private DeveloperNotificationService $notifier = new DeveloperNotificationService(),
    ) {}

    public function healthCheck(Bot $bot): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'bot_id' => $bot->id,
            'bot_name' => $bot->name,
            'webhook_status' => $bot->webhook_status,
            'tg_bot_id' => $bot->tg_bot_id,
            'tg_username' => $bot->tg_username,
        ]);
    }

    public function handle(Request $request, Bot $bot): JsonResponse
    {
        $update = $request->json()->all();

        if (!isset($update['message'])) {
            return response()->json(['ok' => true]);
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $token = decrypt($bot->tg_bot_token);

        $tgUser = $this->getOrCreateTgUser($message);
        $session = $this->sessionService->get($bot->id, $chatId);

        $text = $message['text'] ?? null;
        $contact = $message['contact'] ?? null;

        if ($text === '/start' || !$session) {
            $this->handleStart($bot, $token, $chatId, $tgUser);
        } elseif ($session['state'] === 'lang') {
            $this->handleLanguageSelection($bot, $token, $chatId, $text, $session);
        } elseif ($session['state'] === 'full_name') {
            $this->handleNameInput($bot, $token, $chatId, $text, $session);
        } elseif ($session['state'] === 'phone') {
            $this->handlePhoneInput($bot, $token, $chatId, $contact, $tgUser, $session);
        }

        return response()->json(['ok' => true]);
    }

    private function handleStart(Bot $bot, string $token, int $chatId, TgUser $tgUser): void
    {
        $greeting = $bot->content['greeting'][$tgUser->lang] ?? $bot->content['greeting']['ru'] ?? 'Welcome!';

        $this->telegramService->sendMessage($token, $chatId, $greeting);
        $this->telegramService->sendLanguageKeyboard($token, $chatId);

        $this->sessionService->set($bot->id, $chatId, [
            'state' => 'lang',
            'lang' => $tgUser->lang,
            'first_name' => null,
            'last_name' => null,
        ]);
    }

    private function handleLanguageSelection(Bot $bot, string $token, int $chatId, ?string $text, array $session): void
    {
        $langMap = [
            "🇺🇿 O'zbek" => 'uz',
            '🇬🇧 English' => 'en',
            '🇷🇺 Русский' => 'ru',
        ];

        $lang = $langMap[$text] ?? 'ru';

        TgUser::where('chat_id', $chatId)->update(['lang' => $lang]);

        $messages = [
            'uz' => 'Iltimos, ismingiz va familiyangizni kiriting:',
            'en' => 'Please enter your first and last name:',
            'ru' => 'Пожалуйста, введите имя и фамилию:',
        ];

        $this->telegramService->sendMessage($token, $chatId, $messages[$lang] ?? $messages['ru']);

        $this->sessionService->update($bot->id, $chatId, [
            'state' => 'full_name',
            'lang' => $lang,
        ]);
    }

    private function handleNameInput(Bot $bot, string $token, int $chatId, ?string $text, array $session): void
    {
        $parts = explode(' ', trim($text ?? ''), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';

        TgUser::where('chat_id', $chatId)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $this->telegramService->requestContact($token, $chatId, $session['lang']);

        $this->sessionService->update($bot->id, $chatId, [
            'state' => 'phone',
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);
    }

    private function handlePhoneInput(Bot $bot, string $token, int $chatId, ?array $contact, TgUser $tgUser, array $session): void
    {
        $phone = $contact['phone_number'] ?? null;

        if (!$phone) {
            return;
        }

        TgUser::where('chat_id', $chatId)->update(['phone' => $phone]);

        $approved = !$bot->requires_admin_approval;
        BotClient::updateOrCreate(
            ['bot_id' => $bot->id, 'tg_user_id' => $tgUser->id],
            [
                'approved' => $approved,
                'approved_at' => $approved ? now() : null,
            ]
        );

        $this->notifier->notifyUserRegistered(
            $bot->name,
            $session['first_name'] ?? $tgUser->first_name ?? 'Unknown',
            $session['last_name'] ?? $tgUser->last_name ?? 'Unknown',
            $phone,
            $session['lang'] ?? 'uz'
        );

        $lang = $session['lang'];
        $messages = [
            'uz' => 'Rahmat! Siz ro\'yxatga olindingiz.',
            'en' => 'Thank you! You are registered.',
            'ru' => 'Спасибо! Вы зарегистрированы.',
        ];

        $this->telegramService->sendMessage($token, $chatId, $messages[$lang] ?? $messages['ru']);

        $this->sessionService->forget($bot->id, $chatId);
    }

    private function getOrCreateTgUser(array $message): TgUser
    {
        $from = $message['from'];

        return TgUser::firstOrCreate(
            ['chat_id' => $from['id']],
            [
                'first_name' => $from['first_name'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'username' => $from['username'] ?? null,
            ]
        );
    }
}

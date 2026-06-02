<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Bot;
use App\Models\BotClient;
use App\Models\TgUser;
use App\Services\BotSessionService;
use App\Services\MoySkladService;
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
    ) {}

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

        $integration = $bot->company->integrations()->where('type', 'moisklad')->first();

        $matched = false;
        $mySkladId = null;

        if ($integration && $integration->is_active) {
            $token = $integration->credentials['token'] ?? '';
            $moySkladService = new MoySkladService($token);
            $customer = $moySkladService->findByPhone($phone);

            if ($customer) {
                $matched = true;
                $mySkladId = $customer['id'] ?? null;
            }
        }

        $approved = !$bot->requires_admin_approval;
        BotClient::updateOrCreate(
            ['bot_id' => $bot->id, 'tg_user_id' => $tgUser->id],
            [
                'uuid' => Str::uuid(),
                'mySklad_id' => $mySkladId,
                'matched' => $matched,
                'matched_at' => $matched ? now() : null,
                'approved' => $approved,
                'approved_at' => $approved ? now() : null,
            ]
        );

        $lang = $session['lang'];
        $messages = $matched
            ? [
                'uz' => 'Rahmat! Siz tizimga ulandi.',
                'en' => 'Thank you! You are connected to the system.',
                'ru' => 'Спасибо! Вы подключены к системе.',
            ]
            : [
                'uz' => 'Rahmat! Siz kutiш ro\'yxatida mavjudsiz.',
                'en' => 'Thank you! You are on the waiting list.',
                'ru' => 'Спасибо! Вы в списке ожидания.',
            ];

        $this->telegramService->sendMessage($token, $chatId, $messages[$lang] ?? $messages['ru']);

        $this->sessionService->forget($bot->id, $chatId);
    }

    private function getOrCreateTgUser(array $message): TgUser
    {
        $from = $message['from'];

        return TgUser::updateOrCreate(
            ['chat_id' => $from['id']],
            [
                'uuid' => Str::uuid(),
                'first_name' => $from['first_name'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'username' => $from['username'] ?? null,
            ]
        );
    }
}

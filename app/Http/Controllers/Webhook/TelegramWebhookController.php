<?php

namespace App\Http\Controllers\Webhook;

use App\Models\Bot;
use App\Models\BotClient;
use App\Models\TgUser;
use App\Models\TgUserMessage;
use App\Services\BotSessionService;
use App\Services\DeveloperNotificationService;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        // 🤖 Bot Webhook Handler - Log which bot is processing this request
        // Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', [
        //     'event' => '🤖 WEBHOOK HANDLER START',
        //     'bot_id' => $bot->id,
        //     'bot_name' => $bot->name,
        //     'tg_bot_id' => $bot->tg_bot_id,
        //     'tg_username' => $bot->tg_username,
        // ]);

        $update = $request->json()->all();

        // Log::info('Webhook received', [
        //     'bot_id' => $bot->id,
        //     'bot_name' => $bot->name,
        //     'update' => $update,
        // ]);

        if (!isset($update['message'])) {
            //Log::debug('No message in update', ['update_keys' => array_keys($update)]);
            return response()->json(['ok' => true]);
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];

        try {
            $token = decrypt($bot->tg_bot_token);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt bot token', [
                'bot_id' => $bot->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['ok' => false, 'error' => 'Token decryption failed']);
        }

        try {
            $tgUser = $this->getOrCreateTgUser($message);
            // Log::info('TgUser processed', [
            //     'tg_user_id' => $tgUser->id,
            //     'chat_id' => $chatId,
            // ]);
        } catch (\Exception $e) {
            Log::error('Failed to get/create TgUser', [
                'error' => $e->getMessage(),
                'message' => $message,
            ]);
            return response()->json(['ok' => false, 'error' => 'TgUser creation failed']);
        }

        // Save message to database
        try {
            Log::debug('About to save user message', [
                'bot_id' => $bot->id,
                'tg_user_id' => $tgUser->id,
                'has_message_id' => isset($message['message_id']),
                'message_id' => $message['message_id'] ?? 'missing',
            ]);
            $this->saveUserMessage($bot, $tgUser, $message, $update);
            Log::info('✓ User message saved successfully', [
                'message_id' => $message['message_id'] ?? 'unknown',
                'tg_user_id' => $tgUser->id,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to save user message', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'message_id' => $message['message_id'] ?? 'unknown',
                'tg_user_id' => $tgUser->id,
            ]);
            // Don't fail the entire webhook if message saving fails
        }

        $session = $this->sessionService->get($bot->id, $chatId);
        Log::info('Session retrieved', [
            'bot_id' => $bot->id,
            'chat_id' => $chatId,
            'session' => $session,
        ]);

        $text = $message['text'] ?? null;
        $contact = $message['contact'] ?? null;

        try {
            if ($text === '/start' || !$session) {
                Log::info('Handling /start', ['chat_id' => $chatId]);
                $this->handleStart($bot, $token, $chatId, $tgUser);
            } elseif ($session['state'] === 'lang') {
                Log::info('Handling language selection', ['chat_id' => $chatId, 'text' => $text]);
                $this->handleLanguageSelection($bot, $token, $chatId, $text, $session);
            } elseif ($session['state'] === 'full_name') {
                Log::info('Handling name input', ['chat_id' => $chatId, 'text' => $text]);
                $this->handleNameInput($bot, $token, $chatId, $text, $session);
            } elseif ($session['state'] === 'phone') {
                Log::info('Handling phone input', ['chat_id' => $chatId, 'has_contact' => (bool)$contact]);
                $this->handlePhoneInput($bot, $token, $chatId, $contact, $tgUser, $session);
            } else {
                Log::warning('Unknown session state', [
                    'state' => $session['state'] ?? 'null',
                    'chat_id' => $chatId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing webhook message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }

        Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', [
            'event' => '✅ WEBHOOK HANDLER COMPLETE',
            'bot_name' => $bot->name,
            'status' => 'success',
        ]);

        return response()->json(['ok' => true]);
    }

    private function handleStart(Bot $bot, string $token, int $chatId, TgUser $tgUser): void
    {
        Log::info('━━ HANDLE /START BEGIN ━━', [
            'chat_id' => $chatId,
            'bot_id' => $bot->id,
            'tg_user_id' => $tgUser->id,
        ]);

        try {
            Log::debug('Step 1: Getting greeting message', [
                'bot_content' => $bot->content,
                'tg_user_lang' => $tgUser->lang,
            ]);

            $greeting = $bot->content['greeting'][$tgUser->lang] ?? $bot->content['greeting']['ru'] ?? 'Welcome!';

            Log::debug('Step 2: Greeting message retrieved', [
                'greeting_length' => strlen($greeting),
                'greeting_preview' => substr($greeting, 0, 100),
            ]);

            Log::debug('Step 3: Sending greeting message', [
                'chat_id' => $chatId,
                'token_preview' => substr($token, 0, 10) . '...',
            ]);

            $messageResponse = $this->telegramService->sendMessage($token, $chatId, $greeting);

            Log::debug('Step 4: Greeting sent response', [
                'response' => $messageResponse,
            ]);

            Log::debug('Step 5: Sending language keyboard', [
                'chat_id' => $chatId,
            ]);

            $keyboardResponse = $this->telegramService->sendLanguageKeyboard($token, $chatId);

            Log::debug('Step 6: Keyboard sent response', [
                'response' => $keyboardResponse,
            ]);

            Log::debug('Step 7: Setting session', [
                'bot_id' => $bot->id,
                'chat_id' => $chatId,
                'state' => 'lang',
                'lang' => $tgUser->lang,
            ]);

            $this->sessionService->set($bot->id, $chatId, [
                'state' => 'lang',
                'lang' => $tgUser->lang,
                'first_name' => null,
                'last_name' => null,
            ]);

            Log::info('✓ /START workflow completed successfully', [
                'chat_id' => $chatId,
                'bot_id' => $bot->id,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ /START workflow failed', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'chat_id' => $chatId,
            ]);
            throw $e;
        }
    }

    private function handleLanguageSelection(Bot $bot, string $token, int $chatId, ?string $text, array $session): void
    {
        Log::info('━━ HANDLE LANGUAGE SELECTION ━━', [
            'chat_id' => $chatId,
            'selected_text' => $text,
        ]);

        $langMap = [
            "🇺🇿 O'zbek" => 'uz',
            '🇬🇧 English' => 'en',
            '🇷🇺 Русский' => 'ru',
        ];

        $lang = $langMap[$text] ?? 'ru';

        Log::debug('Language selected', [
            'text' => $text,
            'mapped_lang' => $lang,
        ]);

        TgUser::where('id', (string) $chatId)->update(['lang' => $lang]);

        Log::debug('TgUser lang updated', ['lang' => $lang]);

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
        Log::info('━━ HANDLE NAME INPUT ━━', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        $parts = explode(' ', trim($text ?? ''), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';

        Log::debug('Name parsed', [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        TgUser::where('id', (string) $chatId)->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        Log::debug('TgUser name updated');

        Log::debug('Requesting contact', [
            'lang' => $session['lang'],
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
            Log::warning('No phone provided in contact', ['chat_id' => $chatId]);
            return;
        }

        Log::info('Updating TgUser with phone', [
            'tg_user_id' => (string) $chatId,
            'phone' => $phone,
        ]);

        $tgUser = TgUser::where('id', (string) $chatId)->first();
        if (!$tgUser) {
            Log::error('TgUser not found after first/create', ['id' => (string) $chatId]);
            return;
        }

        $tgUser->update(['phone' => $phone]);
        Log::info('TgUser phone updated', [
            'tg_user_id' => $tgUser->id,
            'phone' => $tgUser->phone,
        ]);

        $approved = !$bot->requires_admin_approval;

        $botId = (string) $bot->id;
        $tgUserId = (string) $tgUser->id;

        Log::info('Creating BotClient', [
            'bot_id' => $botId,
            'bot_id_type' => gettype($botId),
            'tg_user_id' => $tgUserId,
            'tg_user_id_type' => gettype($tgUserId),
            'approved' => $approved,
        ]);

        try {
            $botClient = BotClient::updateOrCreate(
                ['bot_id' => $botId, 'tg_user_id' => $tgUserId],
                [
                    'approved' => $approved,
                    'approved_at' => $approved ? now() : null,
                ]
            );

            // Log::info('BotClient created/updated successfully', [
            //     'bot_client_id' => $botClient->id,
            //     'bot_id' => $botClient->bot_id,
            //     'tg_user_id' => $botClient->tg_user_id,
            // ]);
        } catch (\Exception $e) {
            Log::error('Failed to create BotClient', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return;
        }

        // Log::info('Sending registration notification', [
        //     'bot_name' => $bot->name,
        //     'tg_user_id' => $tgUser->id,
        // ]);

        $this->notifier->notifyUserRegistered(
            $bot->name,
            $session['first_name'] ?? $tgUser->first_name ?? 'Unknown',
            $session['last_name'] ?? $tgUser->last_name ?? 'Unknown',
            $phone,
            $session['lang'] ?? 'uz'
        );

        $lang = $session['lang'] ?? 'uz';
        $messages = [
            'uz' => 'Rahmat! Siz ro\'yxatga olindingiz.',
            'en' => 'Thank you! You are registered.',
            'ru' => 'Спасибо! Вы зарегистрированы.',
        ];

        $this->telegramService->sendMessage($token, $chatId, $messages[$lang] ?? $messages['ru']);

        // Log::info('Registration flow completed', [
        //     'tg_user_id' => $tgUser->id,
        //     'chat_id' => $chatId,
        // ]);

        $this->sessionService->forget($bot->id, $chatId);
    }

    private function getOrCreateTgUser(array $message): TgUser
    {
        $from = $message['from'];
        $telegramUserId = (string) $from['id'];

        return TgUser::firstOrCreate(
            ['id' => $telegramUserId],
            [
                'first_name' => $from['first_name'] ?? null,
                'last_name' => $from['last_name'] ?? null,
                'username' => $from['username'] ?? null,
                'lang' => 'uz',
            ]
        );
    }

    private function saveUserMessage(Bot $bot, TgUser $tgUser, array $message, array $update): void
    {
        $messageId = $message['message_id'];
        $text = $message['text'] ?? null;
        $messageType = 'text';

        Log::debug('saveUserMessage: Processing message', [
            'messageId' => $messageId,
            'bot_id' => $bot->id,
            'tg_user_id' => $tgUser->id,
            'has_text' => isset($message['text']),
            'text_preview' => substr($text ?? '', 0, 50),
        ]);

        // Determine message type
        if (isset($message['photo'])) {
            $messageType = 'photo';
            $text = $message['caption'] ?? '[Photo]';
        } elseif (isset($message['document'])) {
            $messageType = 'document';
            $text = $message['caption'] ?? '[Document]';
        } elseif (isset($message['audio'])) {
            $messageType = 'audio';
            $text = $message['caption'] ?? '[Audio]';
        } elseif (isset($message['video'])) {
            $messageType = 'video';
            $text = $message['caption'] ?? '[Video]';
        } elseif (isset($message['contact'])) {
            $messageType = 'contact';
            $text = '[Contact: ' . ($message['contact']['phone_number'] ?? 'unknown') . ']';
        }

        Log::debug('saveUserMessage: Creating/updating record', [
            'messageId' => $messageId,
            'messageType' => $messageType,
            'textLength' => strlen($text ?? ''),
        ]);

        $record = TgUserMessage::updateOrCreate(
            [
                'bot_id' => (string) $bot->id,
                'message_id' => $messageId,
            ],
            [
                'tg_user_id' => (string) $tgUser->id,
                'message' => $text ?? '[No text]',
                'message_type' => $messageType,
                'raw_update' => $update,
            ]
        );

        Log::info('✓ TgUserMessage record saved', [
            'record_id' => $record->id,
            'bot_id' => $bot->id,
            'tg_user_id' => $tgUser->id,
            'message_id' => $messageId,
            'message_type' => $messageType,
            'created_at' => $record->created_at,
        ]);

        // Notify developer
        $this->notifier->notifyDevelopment(
            '💬 Message Received',
            "bot_id: {$bot->id}\ntg_user_id: {$tgUser->id}\ntype: {$messageType}"
        );
    }
}

<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Services\TelegramService;
use Livewire\Component;

class CreateBotModal extends Component
{
    public string $tg_bot_token = '';
    public string $botUuid = '';
    public bool $tokenVerified = false;
    public ?array $botInfo = null;
    public string $verificationError = '';
    public ?array $webhookInfo = null;
    public string $webhookMessage = '';
    public string $webhookMessageType = '';
    public ?array $currentResponse = null;
    public string $lastAction = '';


    public function verifyToken(): void
    {
        $this->validate([
            'tg_bot_token' => 'required|string|min:10',
        ]);

        try {
            $telegramService = new TelegramService();
            $result = $telegramService->getMe($this->tg_bot_token);

            $this->lastAction = 'GET_BOT_INFO';
            $this->currentResponse = $result;

            if ($result['success']) {
                $this->botInfo = $result['result'];
                $this->tokenVerified = true;
                $this->verificationError = '';
                $this->webhookInfo = null;
                $this->webhookMessage = '';

                // Check if bot exists in database
                $bot = Bot::where('tg_bot_id', $result['result']['id'])->first();

                if (!$bot) {
                    // Create new bot record
                    $bot = Bot::create([
                        'user_id' => auth()->user()->id,
                        'name' => $result['result']['first_name'] ?? 'Telegram Bot',
                        'tg_bot_token' => encrypt($this->tg_bot_token),
                        'tg_bot_id' => $result['result']['id'],
                        'tg_first_name' => $result['result']['first_name'] ?? null,
                        'tg_username' => $result['result']['username'] ?? null,
                        'tg_bot_metadata' => $result['result'],
                        'content' => [
                            'greeting' => ['uz' => '', 'en' => '', 'ru' => ''],
                            'about' => ['uz' => '', 'en' => '', 'ru' => ''],
                        ],
                        'is_active' => true,
                        'webhook_status' => false,
                    ]);
                }

                $this->botUuid = $bot->id;
            } else {
                $this->tokenVerified = false;
                $this->verificationError = $result['message'] ?? 'Failed to verify token';
                $this->botInfo = null;
            }
        } catch (\Exception $e) {
            $this->tokenVerified = false;
            $this->verificationError = 'Error: ' . $e->getMessage();
            $this->botInfo = null;
            $this->lastAction = 'GET_BOT_INFO';
            $this->currentResponse = ['error' => $e->getMessage()];
        }
    }

    public function getWebhookInfo(): void
    {
        if (!$this->tokenVerified) {
            return;
        }

        try {
            $telegramService = new TelegramService();
            $result = $telegramService->getWebhookInfo($this->tg_bot_token);

            $this->lastAction = 'GET_WEBHOOK_INFO';
            $this->currentResponse = $result;

            if ($result['success']) {
                $this->webhookInfo = $result['result'];
                $this->webhookMessage = 'Webhook info retrieved successfully';
                $this->webhookMessageType = 'success';
            } else {
                $this->webhookMessage = $result['message'] ?? 'Failed to get webhook info';
                $this->webhookMessageType = 'error';
                $this->webhookInfo = null;
            }
        } catch (\Exception $e) {
            $this->webhookMessage = 'Error: ' . $e->getMessage();
            $this->webhookMessageType = 'error';
            $this->webhookInfo = null;
            $this->lastAction = 'GET_WEBHOOK_INFO';
            $this->currentResponse = ['error' => $e->getMessage()];
        }
    }

    public function setWebhook(): void
    {
        if (!$this->tokenVerified) {
            return;
        }

        $this->validate([
            'botUuid' => 'required|string|uuid',
        ]);

        try {
            $telegramService = new TelegramService();
            $webhookUrl = 'https://mycode.uz/api/webhook/tg/' . $this->botUuid;
            $result = $telegramService->setWebhook($this->tg_bot_token, $webhookUrl);

            $this->lastAction = 'SET_WEBHOOK';
            $this->currentResponse = $result;

            if ($result['success']) {
                $this->webhookMessage = 'Webhook set successfully';
                $this->webhookMessageType = 'success';
            } else {
                $this->webhookMessage = $result['message'] ?? 'Failed to set webhook';
                $this->webhookMessageType = 'error';
            }
        } catch (\Exception $e) {
            $this->webhookMessage = 'Error: ' . $e->getMessage();
            $this->webhookMessageType = 'error';
            $this->lastAction = 'SET_WEBHOOK';
            $this->currentResponse = ['error' => $e->getMessage()];
        }
    }

    public function deleteWebhook(): void
    {
        if (!$this->tokenVerified) {
            return;
        }

        try {
            $telegramService = new TelegramService();
            $result = $telegramService->deleteWebhookInfo($this->tg_bot_token);

            $this->lastAction = 'DELETE_WEBHOOK';
            $this->currentResponse = $result;

            if ($result['success']) {
                $this->webhookMessage = 'Webhook deleted successfully';
                $this->webhookMessageType = 'success';
                $this->webhookInfo = null;
            } else {
                $this->webhookMessage = $result['message'] ?? 'Failed to delete webhook';
                $this->webhookMessageType = 'error';
            }
        } catch (\Exception $e) {
            $this->webhookMessage = 'Error: ' . $e->getMessage();
            $this->webhookMessageType = 'error';
            $this->lastAction = 'DELETE_WEBHOOK';
            $this->currentResponse = ['error' => $e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.create-bot-modal');
    }
}

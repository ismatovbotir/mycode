<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Services\TelegramService;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateBotModal extends Component
{
    public bool $isOpen = false;
    public bool $showWebhookModal = false;
    public ?Bot $createdBot = null;

    public string $name = '';
    public string $tg_bot_token = '';
    public array $greeting = ['uz' => '', 'en' => '', 'ru' => ''];
    public array $about = ['uz' => '', 'en' => '', 'ru' => ''];
    public string $currentLang = 'uz';
    public bool $requires_admin_approval = false;
    public bool $tokenVerified = false;
    public ?array $botInfo = null;
    public string $verificationError = '';
    public ?array $webhookInfo = null;
    public string $webhookMessage = '';
    public string $webhookMessageType = ''; // 'success', 'error', 'info'

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    public function closeWebhookModal()
    {
        $this->showWebhookModal = false;
        $this->createdBot = null;
        session()->flash('success', 'Bot created successfully!');
        return $this->redirect(route('bots.index'), navigate: true);
    }

    public function retryWebhook(): void
    {
        if (!$this->createdBot) {
            return;
        }

        try {
            $telegramService = new TelegramService();
            $webhookUrl = route('telegram.webhook', ['bot' => $this->createdBot->id], absolute: true);
            $result = $telegramService->setWebhook(decrypt($this->createdBot->tg_bot_token), $webhookUrl);

            if ($result['success']) {
                $this->createdBot->update(['webhook_status' => 'success']);
            } else {
                $this->createdBot->update(['webhook_status' => 'failed']);
            }

            $this->createdBot->refresh();
        } catch (\Exception $e) {
            $this->createdBot->update(['webhook_status' => 'failed']);
            $this->createdBot->refresh();
        }
    }

    public function switchLang($lang)
    {
        $this->currentLang = $lang;
    }

    public function verifyToken(): void
    {
        $this->validate([
            'tg_bot_token' => 'required|string|min:10',
        ]);

        try {
            $telegramService = new TelegramService();
            $result = $telegramService->getMe($this->tg_bot_token);

            if ($result['success']) {
                $this->botInfo = $result['result'];
                $this->name = $this->botInfo['first_name'] ?? '';
                $this->tokenVerified = true;
                $this->verificationError = '';
                $this->webhookInfo = null;
                $this->webhookMessage = '';
            } else {
                $this->tokenVerified = false;
                $this->verificationError = $result['message'] ?? 'Failed to verify token';
                $this->botInfo = null;
            }
        } catch (\Exception $e) {
            $this->tokenVerified = false;
            $this->verificationError = 'Error: ' . $e->getMessage();
            $this->botInfo = null;
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
        }
    }

    public function setWebhook(): void
    {
        if (!$this->tokenVerified) {
            return;
        }

        try {
            $telegramService = new TelegramService();

            // Use created bot ID if available, otherwise generate a temporary one
            $botId = $this->createdBot?->id ?? 'temp-' . Str::uuid();
            $webhookUrl = route('telegram.webhook', ['bot' => $botId], true);
            $result = $telegramService->setWebhook($this->tg_bot_token, $webhookUrl);

            if ($result['success']) {
                $this->webhookMessage = 'Webhook set successfully';
                $this->webhookMessageType = 'success';
                if ($this->createdBot) {
                    $this->createdBot->update(['webhook_status' => true]);
                }
                $this->getWebhookInfo();
            } else {
                $this->webhookMessage = $result['message'] ?? 'Failed to set webhook';
                $this->webhookMessageType = 'error';
                if ($this->createdBot) {
                    $this->createdBot->update(['webhook_status' => false]);
                }
            }
        } catch (\Exception $e) {
            $this->webhookMessage = 'Error: ' . $e->getMessage();
            $this->webhookMessageType = 'error';
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

            if ($result['success']) {
                $this->webhookMessage = 'Webhook deleted successfully';
                $this->webhookMessageType = 'success';
                if ($this->createdBot) {
                    $this->createdBot->update(['webhook_status' => false]);
                }
                $this->webhookInfo = null;
            } else {
                $this->webhookMessage = $result['message'] ?? 'Failed to delete webhook';
                $this->webhookMessageType = 'error';
            }
        } catch (\Exception $e) {
            $this->webhookMessage = 'Error: ' . $e->getMessage();
            $this->webhookMessageType = 'error';
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'tg_bot_token' => 'required|string',
            'greeting.uz' => 'required|string|max:500',
            'about.uz' => 'required|string|max:1000',
        ]);

        if (!$this->tokenVerified) {
            $this->addError('tg_bot_token', 'Please verify the bot token first');
            return;
        }

        abort_if(auth()->user()->bot, 403, 'User already has a bot');

        // Fill missing languages with first language (uz)
        foreach (['en', 'ru'] as $lang) {
            if (empty($this->greeting[$lang])) {
                $this->greeting[$lang] = $this->greeting['uz'];
            }
            if (empty($this->about[$lang])) {
                $this->about[$lang] = $this->about['uz'];
            }
        }

        $bot = Bot::create([
            'user_id' => auth()->user()->id,
            'name' => $this->name,
            'tg_bot_token' => encrypt($this->tg_bot_token),
            'tg_bot_id' => $this->botInfo['id'] ?? null,
            'tg_first_name' => $this->botInfo['first_name'] ?? null,
            'tg_username' => $this->botInfo['username'] ?? null,
            'tg_bot_metadata' => $this->botInfo,
            'content' => [
                'greeting' => $this->greeting,
                'about' => $this->about,
            ],
            'is_active' => true,
            'requires_admin_approval' => $this->requires_admin_approval,
        ]);

        $this->createdBot = $bot;
        $this->isOpen = false;
        $this->showWebhookModal = true;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->tg_bot_token = '';
        $this->greeting = ['uz' => '', 'en' => '', 'ru' => ''];
        $this->about = ['uz' => '', 'en' => '', 'ru' => ''];
        $this->currentLang = 'uz';
        $this->requires_admin_approval = false;
        $this->tokenVerified = false;
        $this->botInfo = null;
        $this->verificationError = '';
        $this->webhookInfo = null;
        $this->webhookMessage = '';
        $this->webhookMessageType = '';
    }

    public function render()
    {
        return view('livewire.create-bot-modal');
    }
}

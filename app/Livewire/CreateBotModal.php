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

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'tg_bot_token' => 'required|string',
            'greeting.uz' => 'required|string|max:500',
            'about.uz' => 'required|string|max:1000',
        ]);

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
            'webhook_secret' => Str::uuid(),
            'content' => [
                'greeting' => $this->greeting,
                'about' => $this->about,
            ],
            'is_active' => true,
            'requires_admin_approval' => $this->requires_admin_approval,
        ]);

        // Set Telegram webhook
        try {
            $telegramService = new TelegramService();
            $webhookUrl = route('telegram.webhook', ['bot' => $bot->id], true);
            $result = $telegramService->setWebhook(decrypt($bot->tg_bot_token), $webhookUrl);

            if ($result['success']) {
                $bot->update(['webhook_status' => 'success']);
            } else {
                \Log::warning('Failed to set webhook', [
                    'bot_id' => $bot->id,
                    'message' => $result['message'],
                ]);
                $bot->update(['webhook_status' => 'failed']);
            }
        } catch (\Exception $e) {
            \Log::error('Exception setting webhook', ['bot_id' => $bot->id, 'error' => $e->getMessage()]);
            $bot->update(['webhook_status' => 'failed']);
        }

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
    }

    public function render()
    {
        return view('livewire.create-bot-modal');
    }
}

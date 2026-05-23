<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Services\TelegramService;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateBotModal extends Component
{
    public bool $isOpen = false;

    public string $name = '';
    public string $tg_bot_token = '';
    public array $greeting = ['uz' => '', 'ru' => '', 'tj' => '', 'kk' => ''];
    public array $about = ['uz' => '', 'ru' => '', 'tj' => '', 'kk' => ''];
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

        // Fill missing languages with first language (uz)
        foreach (['ru', 'tj', 'kk'] as $lang) {
            if (empty($this->greeting[$lang])) {
                $this->greeting[$lang] = $this->greeting['uz'];
            }
            if (empty($this->about[$lang])) {
                $this->about[$lang] = $this->about['uz'];
            }
        }

        $company = auth()->user()->company;

        $bot = Bot::create([
            'uuid' => Str::uuid(),
            'company_id' => $company->id,
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
            $webhookUrl = route('telegram.webhook', ['bot' => $bot->uuid], true);
            $telegramService->setWebhook(decrypt($bot->tg_bot_token), $webhookUrl);
        } catch (\Exception $e) {
            \Log::error('Failed to set webhook', ['bot_id' => $bot->id, 'error' => $e->getMessage()]);
        }

        $this->dispatch('bot-created');
        $this->closeModal();
        session()->flash('success', 'Bot created successfully! Webhook set up completed.');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->tg_bot_token = '';
        $this->greeting = ['uz' => '', 'ru' => '', 'tj' => '', 'kk' => ''];
        $this->about = ['uz' => '', 'ru' => '', 'tj' => '', 'kk' => ''];
        $this->currentLang = 'uz';
        $this->requires_admin_approval = false;
    }

    public function render()
    {
        return view('livewire.create-bot-modal');
    }
}

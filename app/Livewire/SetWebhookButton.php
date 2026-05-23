<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Services\TelegramService;
use Livewire\Component;

class SetWebhookButton extends Component
{
    public Bot $bot;
    public bool $loading = false;

    public function setWebhook()
    {
        abort_if(!auth()->user()->can('update', $this->bot), 403);
        $this->loading = true;

        try {
            $telegramService = new TelegramService();
            $webhookUrl = route('telegram.webhook', ['bot' => $this->bot->id], true);
            $telegramService->setWebhook(decrypt($this->bot->tg_bot_token), $webhookUrl);
            $this->bot->update(['webhook_status' => 'success']);
            session()->flash('success', 'Webhook set successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to set webhook', ['bot_id' => $this->bot->id, 'error' => $e->getMessage()]);
            $this->bot->update(['webhook_status' => 'failed']);
            session()->flash('error', 'Failed to set webhook: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        return view('livewire.set-webhook-button');
    }
}

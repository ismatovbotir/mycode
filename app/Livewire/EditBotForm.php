<?php

namespace App\Livewire;

use App\Models\Bot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditBotForm extends Component
{
    public Bot $bot;
    public string $name = '';
    public bool $requires_admin_approval = false;
    public array $greeting = [];
    public array $about = [];
    public string $currentLang = 'uz';

    public function mount()
    {
        $this->name = $this->bot->name;
        $this->requires_admin_approval = $this->bot->requires_admin_approval;
        $this->greeting = $this->bot->content['greeting'] ?? [];
        $this->about = $this->bot->content['about'] ?? [];
    }

    public function switchLang(string $lang)
    {
        $this->currentLang = $lang;
    }

    public function save()
    {
        abort_if(!Auth::user()->can('update', $this->bot), 403);

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'requires_admin_approval' => ['boolean'],
            'greeting' => ['required', 'array'],
            'greeting.uz' => ['required', 'string', 'max:500'],
            'greeting.en' => ['nullable', 'string', 'max:500'],
            'greeting.ru' => ['nullable', 'string', 'max:500'],
            'about' => ['required', 'array'],
            'about.uz' => ['required', 'string', 'max:1000'],
            'about.en' => ['nullable', 'string', 'max:1000'],
            'about.ru' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach (['en', 'ru'] as $lang) {
            if (empty($this->greeting[$lang])) {
                $this->greeting[$lang] = $this->greeting['uz'];
            }
            if (empty($this->about[$lang])) {
                $this->about[$lang] = $this->about['uz'];
            }
        }

        $this->bot->update([
            'name' => $this->name,
            'requires_admin_approval' => $this->requires_admin_approval,
            'content' => [
                'greeting' => $this->greeting,
                'about' => $this->about,
            ],
        ]);

        session()->flash('success', 'Bot updated successfully!');
        return redirect()->route('bots.show', $this->bot);
    }

    public function render()
    {
        return view('livewire.edit-bot-form');
    }
}

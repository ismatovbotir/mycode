<?php

namespace App\Livewire;

use App\Models\Bot;
use App\Models\BotEventTemplate;
use Livewire\Component;

class BotEventTemplates extends Component
{
    public Bot $bot;
    public string $currentEvent = 'supply';
    public string $currentLang = 'uz';
    public array $templates = [];

    public const EVENT_TYPES = [
        'supply' => 'Supply (Приход товара)',
        'demand' => 'Demand (Расход/Отгрузка)',
        'paymentin' => 'Payment In (Входящий платёж)',
        'paymentout' => 'Payment Out (Исходящий платёж)',
        'salesreturn' => 'Sales Return (Возврат)',
    ];

    public function mount(Bot $bot)
    {
        $this->bot = $bot;
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        foreach (array_keys(self::EVENT_TYPES) as $eventType) {
            $template = $this->bot->eventTemplates()->where('event_type', $eventType)->first();
            $this->templates[$eventType] = [
                'uz' => $template?->messages['uz'] ?? '',
                'en' => $template?->messages['en'] ?? '',
                'ru' => $template?->messages['ru'] ?? '',
            ];
        }
    }

    public function switchEvent($eventType)
    {
        $this->currentEvent = $eventType;
    }

    public function switchLang($lang)
    {
        $this->currentLang = $lang;
    }

    public function saveTemplate($eventType)
    {
        $messages = $this->templates[$eventType];

        foreach (['en', 'ru'] as $lang) {
            if (empty($messages[$lang])) {
                $messages[$lang] = $messages['uz'];
            }
        }

        BotEventTemplate::updateOrCreate(
            ['bot_id' => $this->bot->id, 'event_type' => $eventType],
            ['messages' => $messages]
        );

        $this->dispatch('notify', message: 'Template saved successfully!');
    }

    public function render()
    {
        return view('livewire.bot-event-templates');
    }
}

<?php
// app/Livewire/WebhookEventToggle.php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Bot;
use Livewire\Component;

class WebhookEventToggle extends Component
{
    public Bot $bot;
    public array $enabledEvents = [];
    public array $allEvents = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        // Get all active webhook event types
        $this->allEvents = \App\Models\WebhookEventType::where('is_active', true)
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'event_type' => $event->event_type,
                'icon' => $event->icon,
                'description' => $event->description,
                'is_enabled' => $this->bot->webhookEventTypes()
                    ->where('webhook_event_type_id', $event->id)
                    ->where('is_enabled', true)
                    ->exists(),
            ])
            ->toArray();
    }

    public function toggleEvent(string $eventTypeId)
    {
        $this->authorize('update', $this->bot);

        $eventType = \App\Models\WebhookEventType::findOrFail($eventTypeId);

        // Check if relation exists
        $pivot = $this->bot->webhookEventTypes()
            ->where('webhook_event_type_id', $eventTypeId)
            ->first();

        if ($pivot) {
            // Toggle existing relation
            $newStatus = !$pivot->pivot->is_enabled;
            $this->bot->webhookEventTypes()
                ->updateExistingPivot($eventTypeId, ['is_enabled' => $newStatus]);
        } else {
            // Create new relation
            $this->bot->webhookEventTypes()
                ->attach($eventTypeId, ['is_enabled' => true]);
        }

        // Reload events
        $this->loadEvents();

        session()->flash('success', 'Webhook event updated!');
    }

    public function render()
    {
        return view('livewire.webhook-event-toggle');
    }
}

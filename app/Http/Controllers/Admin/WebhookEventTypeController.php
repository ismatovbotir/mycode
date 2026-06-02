<?php
// app/Http/Controllers/Admin/WebhookEventTypeController.php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\WebhookEventType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebhookEventTypeController
{
    public function index(): View
    {
        $eventTypes = WebhookEventType::latest()->paginate(20);
        return view('admin.webhook-event-types.index', compact('eventTypes'));
    }

    public function create(): View
    {
        return view('admin.webhook-event-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', 'unique:webhook_event_types'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        WebhookEventType::create($validated);

        return redirect()->route('admin.webhook-event-types.index')
            ->with('success', 'Webhook event type created successfully!');
    }

    public function edit(WebhookEventType $eventType): View
    {
        return view('admin.webhook-event-types.edit', compact('eventType'));
    }

    public function update(Request $request, WebhookEventType $eventType): RedirectResponse
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string', 'unique:webhook_event_types,event_type,' . $eventType->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['required', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        $eventType->update($validated);

        return redirect()->route('admin.webhook-event-types.index')
            ->with('success', 'Webhook event type updated successfully!');
    }

    public function destroy(WebhookEventType $eventType): RedirectResponse
    {
        $eventType->delete();

        return redirect()->route('admin.webhook-event-types.index')
            ->with('success', 'Webhook event type deleted successfully!');
    }
}

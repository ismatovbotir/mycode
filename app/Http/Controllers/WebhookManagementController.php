<?php
// app/Http/Controllers/WebhookManagementController.php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bot;
use Illuminate\View\View;

class WebhookManagementController extends Controller
{
    public function show(Bot $bot): View
    {
        $this->authorize('view', $bot);

        $integration = $bot->integrations()
            ->where('type', 'moysklad')
            ->first();

        $webhookUrl = route('moisklad.webhook', $bot, true);

        // Get recent webhook events
        $recentEvents = $bot->webhookEvents()
            ->latest()
            ->limit(50)
            ->get();

        // Get event statistics
        $eventStats = $bot->webhookEvents()
            ->groupBy('event_type')
            ->selectRaw('event_type, count(*) as count, max(created_at) as last_received')
            ->get()
            ->keyBy('event_type');

        return view('webhooks.management', compact(
            'bot',
            'integration',
            'webhookUrl',
            'recentEvents',
            'eventStats'
        ));
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bot;
use Illuminate\Http\JsonResponse;

class BotStatsController
{
    public function show(Bot $bot): JsonResponse
    {
        $this->authorize('view', $bot);

        $totalClients = $bot->clients()->count();
        $matchedClients = $bot->clients()->where('matched', true)->count();
        $totalNotifications = $bot->notifications()->count();
        $sentNotifications = $bot->notifications()->where('tg_status', 'sent')->count();
        $failedNotifications = $bot->notifications()->where('tg_status', 'failed')->count();

        $eventStats = $bot->webhookEvents()
            ->groupBy('event_type')
            ->selectRaw('event_type, count(*) as count, sum(case when status = "sent" then 1 else 0 end) as sent')
            ->get()
            ->keyBy('event_type');

        return response()->json([
            'data' => [
                'clients' => [
                    'total' => $totalClients,
                    'matched' => $matchedClients,
                    'unmatched' => $totalClients - $matchedClients,
                ],
                'notifications' => [
                    'total' => $totalNotifications,
                    'sent' => $sentNotifications,
                    'failed' => $failedNotifications,
                    'queued' => $bot->notifications()->where('tg_status', 'queued')->count(),
                ],
                'events_by_type' => $eventStats,
            ],
        ]);
    }
}

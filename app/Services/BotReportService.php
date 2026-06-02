<?php
// app/Services/BotReportService.php

declare(strict_types=1);

namespace App\Services;

use App\Models\Bot;
use Carbon\Carbon;

class BotReportService
{
    public function generateDailyReport(Bot $bot, ?Carbon $date = null): array
    {
        $date = $date ?? now();
        $startOfDay = $date->clone()->startOfDay();
        $endOfDay = $date->clone()->endOfDay();

        $totalClients = $bot->clients()->count();
        $newClientsToday = $bot->botClients()
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();
        $approvedClients = $bot->botClients()
            ->where('approved', true)
            ->count();

        $matchedClients = $bot->botClients()
            ->whereNotNull('client_id')
            ->count();

        // Get webhook events from today
        $webhookEvents = $bot->webhookEvents()
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->groupBy('event_type')
            ->selectRaw('event_type, count(*) as count')
            ->get()
            ->pluck('count', 'event_type')
            ->toArray();

        // Get notifications sent today
        $notificationsSent = $bot->notifications()
            ->where('tg_status', 'sent')
            ->whereBetween('sent_at', [$startOfDay, $endOfDay])
            ->count();

        return [
            'date' => $date->format('Y-m-d'),
            'bot_name' => $bot->name,
            'stats' => [
                'total_clients' => $totalClients,
                'new_clients_today' => $newClientsToday,
                'approved_clients' => $approvedClients,
                'matched_clients' => $matchedClients,
                'notifications_sent' => $notificationsSent,
            ],
            'events_by_type' => $webhookEvents,
        ];
    }

    public function formatReportMessage(array $report): string
    {
        $botName = $report['bot_name'];
        $date = $report['date'];
        $stats = $report['stats'];
        $events = $report['events_by_type'];

        $message = "📊 <b>Daily Report: {$botName}</b>\n";
        $message .= "📅 <i>{$date}</i>\n\n";

        $message .= "👥 <b>Client Statistics:</b>\n";
        $message .= "• Total: {$stats['total_clients']}\n";
        $message .= "• New today: {$stats['new_clients_today']}\n";
        $message .= "• Approved: {$stats['approved_clients']}\n";
        $message .= "• Matched: {$stats['matched_clients']}\n\n";

        $message .= "📬 <b>Notifications:</b>\n";
        $message .= "• Sent: {$stats['notifications_sent']}\n\n";

        if (!empty($events)) {
            $message .= "📈 <b>Events by Type:</b>\n";
            foreach ($events as $type => $count) {
                $icon = $this->getEventIcon($type);
                $message .= "• {$icon} {$type}: {$count}\n";
            }
        }

        return $message;
    }

    private function getEventIcon(string $type): string
    {
        return match ($type) {
            'demand' => '📦',
            'supply' => '📥',
            'paymentin' => '💰',
            'paymentout' => '💸',
            default => '📌',
        };
    }
}

<?php
// app/Services/BroadcastService.php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\SendTelegramNotification;
use App\Models\Broadcast;
use App\Models\BotClient;
use Illuminate\Database\Eloquent\Collection;

class BroadcastService
{
    public function send(Broadcast $broadcast): void
    {
        $recipients = $this->getRecipients($broadcast);

        foreach ($recipients as $client) {
            $notification = $broadcast->bot->notifications()->create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'bot_client_id' => $client->id,
                'broadcast_id' => $broadcast->id,
                'message' => $broadcast->message,
                'tg_status' => 'queued',
            ]);

            SendTelegramNotification::dispatch($notification)
                ->onQueue('telegram');
        }

        $broadcast->update(['status' => 'sending']);
    }

    public function getRecipients(Broadcast $broadcast): Collection
    {
        if ($broadcast->group_id) {
            return BotClient::where('bot_id', $broadcast->bot_id)
                ->whereHas('groups', function ($query) {
                    $query->where('client_groups.id', request()->group_id);
                })
                ->get();
        }

        return $broadcast->bot->clients()
            ->where('matched', true)
            ->get();
    }
}

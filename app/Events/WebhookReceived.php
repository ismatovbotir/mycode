<?php

namespace App\Events;

use App\Models\WebhookEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public WebhookEvent $event) {}
}

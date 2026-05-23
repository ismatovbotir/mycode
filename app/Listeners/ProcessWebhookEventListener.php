<?php

namespace App\Listeners;

use App\Events\WebhookReceived;
use App\Jobs\ProcessWebhookEvent;

class ProcessWebhookEventListener
{
    public function __invoke(WebhookReceived $event): void
    {
        ProcessWebhookEvent::dispatch($event->event)->onQueue('default');
    }
}

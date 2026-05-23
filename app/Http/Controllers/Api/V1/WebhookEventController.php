<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookEventController
{
    public function index(Bot $bot, Request $request): JsonResponse
    {
        $this->authorize('view', $bot);

        $query = $bot->webhookEvents();

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('event_type')) {
            $query->where('event_type', $request->get('event_type'));
        }

        $events = $query->latest()->paginate(20);

        return response()->json([
            'data' => $events->items(),
            'meta' => [
                'total' => $events->total(),
                'per_page' => $events->perPage(),
                'current_page' => $events->currentPage(),
            ],
        ]);
    }

    public function show(Bot $bot, string $uuid): JsonResponse
    {
        $this->authorize('view', $bot);

        $event = $bot->webhookEvents()->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'data' => [
                'uuid' => $event->uuid,
                'type' => $event->event_type,
                'status' => $event->status,
                'payload' => $event->payload,
                'created_at' => $event->created_at,
            ],
        ]);
    }
}

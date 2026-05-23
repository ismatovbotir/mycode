<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bot;
use App\Models\BotEventTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BotEventTemplateController
{
    public function index(Bot $bot): JsonResponse
    {
        $this->authorize('view', $bot);

        $templates = $bot->eventTemplates()->get();

        return response()->json([
            'data' => $templates,
        ]);
    }

    public function store(Request $request, Bot $bot): JsonResponse
    {
        $this->authorize('update', $bot);

        $validated = $request->validate([
            'event_type' => 'required|string|unique:bot_event_templates,event_type,null,id,bot_id,' . $bot->id,
            'messages' => 'required|array',
            'messages.uz' => 'required|string',
            'messages.ru' => 'required|string',
            'messages.tj' => 'required|string',
            'messages.kk' => 'required|string',
        ]);

        $template = $bot->eventTemplates()->create([
            'event_type' => $validated['event_type'],
            'messages' => $validated['messages'],
        ]);

        return response()->json([
            'data' => $template,
        ], 201);
    }

    public function update(Request $request, Bot $bot, BotEventTemplate $template): JsonResponse
    {
        $this->authorize('update', $bot);

        if ($template->bot_id !== $bot->id) {
            abort(404);
        }

        $validated = $request->validate([
            'messages' => 'required|array',
            'messages.uz' => 'required|string',
            'messages.ru' => 'required|string',
            'messages.tj' => 'required|string',
            'messages.kk' => 'required|string',
        ]);

        $template->update($validated);

        return response()->json([
            'data' => $template,
        ]);
    }

    public function destroy(Bot $bot, BotEventTemplate $template): JsonResponse
    {
        $this->authorize('update', $bot);

        if ($template->bot_id !== $bot->id) {
            abort(404);
        }

        $template->delete();

        return response()->json(null, 204);
    }
}

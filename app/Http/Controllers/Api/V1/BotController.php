<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bot;
use App\Models\Company;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotController
{
    public function index(Company $company): JsonResponse
    {
        $this->authorize('view', $company);

        $bots = $company->bots()->get();

        return response()->json([
            'data' => $bots->map(fn($bot) => $this->botResource($bot)),
        ]);
    }

    public function store(Request $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tg_bot_token' => 'required|string',
            'content' => 'required|array',
            'content.greeting' => 'required|array',
            'content.about' => 'required|array',
        ]);

        $bot = new Bot([
            'uuid' => Str::uuid(),
            'company_id' => $company->id,
            'name' => $validated['name'],
            'tg_bot_token' => encrypt($validated['tg_bot_token']),
            'webhook_secret' => Str::uuid(),
            'content' => $validated['content'],
            'is_active' => true,
        ]);

        $bot->save();

        // Set Telegram webhook
        $telegramService = new TelegramService();
        $webhookUrl = route('telegram.webhook', ['bot' => $bot->uuid]);
        $telegramService->setWebhook(decrypt($bot->tg_bot_token), $webhookUrl);

        return response()->json([
            'data' => $this->botResource($bot),
            'webhook' => [
                'url' => $webhookUrl,
                'secret' => $bot->webhook_secret,
            ],
        ], 201);
    }

    public function show(Bot $bot): JsonResponse
    {
        $this->authorize('view', $bot);

        return response()->json([
            'data' => $this->botResource($bot),
        ]);
    }

    public function update(Request $request, Bot $bot): JsonResponse
    {
        $this->authorize('update', $bot);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'content' => 'array',
            'content.greeting' => 'array',
            'content.about' => 'array',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $bot->name = $validated['name'];
        }

        if (isset($validated['content'])) {
            $bot->content = array_merge($bot->content ?? [], $validated['content']);
        }

        if (isset($validated['is_active'])) {
            $bot->is_active = $validated['is_active'];
        }

        $bot->save();

        return response()->json([
            'data' => $this->botResource($bot),
        ]);
    }

    public function destroy(Bot $bot): JsonResponse
    {
        $this->authorize('delete', $bot);

        // Delete Telegram webhook
        $telegramService = new TelegramService();
        $telegramService->deleteWebhook(decrypt($bot->tg_bot_token));

        $bot->delete();

        return response()->json(null, 204);
    }

    private function botResource(Bot $bot): array
    {
        return [
            'uuid' => $bot->uuid,
            'name' => $bot->name,
            'webhook_secret' => $bot->webhook_secret,
            'content' => $bot->content,
            'is_active' => $bot->is_active,
            'clients_count' => $bot->clients->count(),
            'created_at' => $bot->created_at,
        ];
    }
}

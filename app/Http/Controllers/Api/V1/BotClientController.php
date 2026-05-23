<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bot;
use Illuminate\Http\JsonResponse;

class BotClientController
{
    public function index(Bot $bot): JsonResponse
    {
        $this->authorize('view', $bot);

        $clients = $bot->clients()->with('tgUser')->get();

        return response()->json([
            'data' => $clients->map(fn($client) => [
                'uuid' => $client->uuid,
                'name' => "{$client->tgUser->first_name} {$client->tgUser->last_name}",
                'phone' => $client->tgUser->phone,
                'matched' => $client->matched,
                'matched_at' => $client->matched_at,
                'created_at' => $client->created_at,
            ]),
        ]);
    }
}

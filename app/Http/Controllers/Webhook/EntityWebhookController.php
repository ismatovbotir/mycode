<?php
// app/Http/Controllers/Webhook/EntityWebhookController.php

declare(strict_types=1);

namespace App\Http\Controllers\Webhook;

use App\Models\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EntityWebhookController
{
    public function handle(Request $request, UserEntity $user_entity): JsonResponse
    {
        try {
            Log::channel('webhook')->info('Entity webhook received', [
                'user_entity_id' => $user_entity->id,
                'entity_type' => $user_entity->entity->type,
                'action' => $request->input('action'),
                'body' => $request->all(),
            ]);

            // Process the webhook event
            // This could trigger notifications, data sync, etc.

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            Log::channel('webhook')->error('Entity webhook error', [
                'user_entity_id' => $user_entity->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

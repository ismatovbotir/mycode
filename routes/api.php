<?php

use App\Http\Controllers\Api\V1\BotClientController;
use App\Http\Controllers\Api\V1\BotController;
use App\Http\Controllers\Api\V1\BotEventTemplateController;
use App\Http\Controllers\Api\V1\BotStatsController;
use App\Http\Controllers\Api\V1\IntegrationController;
use App\Http\Controllers\Api\V1\WebhookEventController;
use App\Http\Controllers\Webhook\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/{bot:uuid}', [WebhookController::class, 'handle']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('companies.bots', BotController::class);
    Route::apiResource('companies.integrations', IntegrationController::class);
    Route::apiResource('bots.templates', BotEventTemplateController::class);
    Route::apiResource('bots.clients', BotClientController::class)->only('index');
    Route::apiResource('bots.events', WebhookEventController::class)->only('index', 'show');
    Route::get('bots/{bot:uuid}/stats', BotStatsController::class . '@show')->name('bots.stats');
});

<?php

use App\Http\Controllers\Webhook\TelegramWebhookController;
use App\Http\Controllers\Webhook\EntityWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/webhook/tg/{bot}', [TelegramWebhookController::class, 'healthCheck'])
    ->name('telegram.webhook.health');

Route::post('/webhook/tg/{bot}', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

Route::post('/webhook/ms/{user_entity}', [EntityWebhookController::class, 'handle'])
    ->name('webhook.moysklad.entity');

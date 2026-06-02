<?php

use App\Http\Controllers\Webhook\TelegramWebhookController;
use App\Http\Controllers\Webhook\MoyskladController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/tg/{bot:id}', [TelegramWebhookController::class, 'handle']);
Route::post('/webhook/ms/{user_entity:id}', [MoyskladController::class, 'handle']);

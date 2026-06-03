<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IntegrationWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::model('bot', \App\Models\Bot::class);

// ---------------------------------------------------------------------------
// Auth routes — locale-prefixed: /uz/register  /ru/register  /en/register
// Default (no prefix) falls back to locale 'uz'
// ---------------------------------------------------------------------------
$authRoutes = function (): void {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
};

// Locale-prefixed: /uz/register, /ru/register, /en/register
Route::middleware(['guest', 'set.locale'])
    ->prefix('{locale}')
    ->where(['locale' => 'uz|ru|en'])
    ->name('locale.')
    ->group($authRoutes);

// Default (no prefix) — resolves locale from session or falls back to 'uz'
Route::middleware(['guest', 'set.locale'])
    ->group($authRoutes);
// ---------------------------------------------------------------------------

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// МойСклад Setup Routes (before auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/moysklad-setup', [\App\Http\Controllers\MoySkladSetupController::class, 'index'])->name('moysklad-setup.index');
});

// User Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard-admin'))->name('dashboard');

    Route::get('/bots', [BotController::class, 'index'])->name('bots.index');
    Route::get('/bots/{bot}', [BotController::class, 'show'])->name('bots.show');
    Route::put('/bots/{bot}', [BotController::class, 'update'])->name('bots.update');
    Route::patch('/bots/{bot}/toggle-active', [BotController::class, 'toggleActive'])->name('bots.toggleActive');
    Route::get('/bots/{bot}/clients', [BotController::class, 'clients'])->name('bots.clients');
    Route::post('/bots/{bot}/clients/{client}/approve', [BotController::class, 'approveClient'])->name('bots.clients.approve');
    Route::post('/bots/{bot}/clients/{client}/reject', [BotController::class, 'rejectClient'])->name('bots.clients.reject');

    Route::get('/bots/{bot}/integrations', [IntegrationWebController::class, 'index'])->name('integrations.index');
    Route::post('/bots/{bot}/integrations', [IntegrationWebController::class, 'store'])->name('integrations.store');
    Route::delete('/bots/{bot}/integrations/{integration}', [IntegrationWebController::class, 'destroy'])->name('integrations.destroy');
    Route::get('/bots/{bot}/webhooks', [\App\Http\Controllers\WebhookManagementController::class, 'show'])->name('webhooks.show');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    Route::get('/entities', fn() => view('admin.entities'))->name('entities.index');
    Route::post('/entities/{entity}/activate', [\App\Http\Controllers\EntityActivationController::class, 'activate'])->name('entities.activate');
    Route::post('/entities/{entity}/deactivate', [\App\Http\Controllers\EntityActivationController::class, 'deactivate'])->name('entities.deactivate');

    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
});

// Super Admin Dashboard Routes
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/management', fn() => view('admin.management-menu'))->name('management');
    Route::get('/load', fn() => view('admin.load'))->name('load');
    Route::get('/queues', fn() => view('admin.queues'))->name('queues');
    Route::get('/failed-jobs', fn() => view('admin.failed-jobs'))->name('failed-jobs');

    // Webhook Event Types Management
    Route::resource('webhook-event-types', \App\Http\Controllers\Admin\WebhookEventTypeController::class);

    // Integration Fields Management
    Route::resource('integration-fields', \App\Http\Controllers\Admin\IntegrationFieldController::class);

    // Entities Management
    Route::get('/entities', [\App\Http\Controllers\Admin\EntityController::class, 'index'])->name('entities.index');

    // МойСклад Webhooks Management
    Route::get('/moysklad-webhooks', fn() => view('admin.moysklad-webhooks'))->name('moysklad-webhooks.index');
});

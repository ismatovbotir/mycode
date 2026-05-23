<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IntegrationWebController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::model('bot', \App\Models\Bot::class);

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// User Dashboard Routes
Route::middleware('auth')->group(function () {
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

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    Route::get('/company/settings', [CompanyController::class, 'settings'])->name('company.settings');
    Route::put('/company/settings', [CompanyController::class, 'updateSettings'])->name('company.updateSettings');
});

// Super Admin Dashboard Routes
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/companies', fn() => view('admin.companies'))->name('companies');
    Route::get('/load', fn() => view('admin.load'))->name('load');
    Route::get('/queues', fn() => view('admin.queues'))->name('queues');
    Route::get('/failed-jobs', fn() => view('admin.failed-jobs'))->name('failed-jobs');
});

Route::post('/webhook/telegram/{bot}', [\App\Http\Controllers\Webhook\TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

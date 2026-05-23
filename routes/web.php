<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\IntegrationWebController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

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
    Route::get('/bots/{bot:uuid}', [BotController::class, 'show'])->name('bots.show');
    Route::put('/bots/{bot:uuid}', [BotController::class, 'update'])->name('bots.update');
    Route::patch('/bots/{bot:uuid}/toggle-active', [BotController::class, 'toggleActive'])->name('bots.toggleActive');
    Route::get('/bots/{bot:uuid}/clients', [BotController::class, 'clients'])->name('bots.clients');
    Route::post('/bots/{bot:uuid}/clients/{client:uuid}/approve', [BotController::class, 'approveClient'])->name('bots.clients.approve');
    Route::post('/bots/{bot:uuid}/clients/{client:uuid}/reject', [BotController::class, 'rejectClient'])->name('bots.clients.reject');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    Route::get('/integrations', [IntegrationWebController::class, 'index'])->name('integrations.index');
    Route::post('/integrations', [IntegrationWebController::class, 'store'])->name('integrations.store');
    Route::delete('/integrations/{integration:uuid}', [IntegrationWebController::class, 'destroy'])->name('integrations.destroy');

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

Route::post('/webhook/telegram/{bot:uuid}', [\App\Http\Controllers\Webhook\TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');

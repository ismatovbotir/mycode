@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($bot)
        <!-- Bot Management View (when bot exists) -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $bot->name }}</h1>
                    <p class="text-gray-600 mt-1">@{{ $bot->tg_username }}</p>
                </div>
                <livewire:toggle-bot-active :bot="$bot" :key="'toggle-'.$bot->id" />
            </div>
        </div>

        <!-- Bot Quick Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Total Clients</p>
                <p class="text-2xl font-bold mt-1">{{ $bot->clients->count() }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Webhook Status</p>
                <p class="text-sm font-semibold mt-1">
                    @if($bot->webhook_status)
                        <span class="text-green-600">✓ Connected</span>
                    @else
                        <span class="text-amber-600">⚠ Not Configured</span>
                    @endif
                </p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Status</p>
                <p class="text-sm font-semibold mt-1">
                    @if($bot->is_active)
                        <span class="text-green-600">🟢 Active</span>
                    @else
                        <span class="text-gray-600">⚫ Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Telegram Account Linking -->
        <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm">
            @include('settings.telegram-link')
        </div>

        <!-- Webhook Tester & Configuration -->
        <livewire:create-bot-modal />

        <!-- Bot Settings -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Bot Settings</h2>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <livewire:edit-bot-form :bot="$bot" />
            </div>
        </div>
    @else
        <!-- Bot Creation View (when no bot exists) -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Set Up Your Telegram Bot</h1>
            <p class="text-gray-600 mt-2">Get started by creating and configuring your first Telegram bot</p>
        </div>

        <div class="max-w-2xl">
            <!-- Webhook Tester (Bot Creation Form) -->
            <livewire:create-bot-modal />
        </div>
    @endif
</div>
@endsection

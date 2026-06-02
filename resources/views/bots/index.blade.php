@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Your Bot</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your Telegram bot</p>
        </div>
    </div>

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
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg">🤖</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold">{{ $bot->name }}</h3>
                                <livewire:toggle-bot-active :bot="$bot" :key="'toggle-'.$bot->id" />
                            </div>
                            <div class="flex items-center gap-4 mt-1">
                                <p class="text-sm text-gray-500">{{ $bot->clients->count() }} clients</p>
                                <livewire:set-webhook-button :bot="$bot" :key="'webhook-'.$bot->id" />
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('bots.show', $bot) }}" class="text-xs text-brand-600 px-3 py-1.5 rounded-lg border border-brand-200 hover:bg-brand-50 transition-colors ml-4">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 mb-1">No bot yet</h3>
            <p class="text-xs text-gray-500 mb-4">Create a Telegram bot to get started</p>
            <livewire:create-bot-modal />
        </div>
    @endif
</div>
@endsection

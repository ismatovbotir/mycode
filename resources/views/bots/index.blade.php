@extends('layouts.admin')

@section('content')
<div class="p-6">
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

    <!-- Webhook Tester -->
    <livewire:create-bot-modal />

    <!-- Current Bot (if exists) -->
    @if($bot)
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Active Bot</h2>
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-5 py-4">
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
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

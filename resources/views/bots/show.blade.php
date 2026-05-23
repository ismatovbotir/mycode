@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $bot->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage bot configuration and settings</p>
        </div>
        <livewire:toggle-bot-active :bot="$bot" :key="'toggle-'.$bot->id" />
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Webhook Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-600 mb-2">Webhook URL</p>
            <div class="flex items-center gap-2">
                <code class="flex-1 text-xs bg-gray-50 p-3 rounded-lg text-gray-800 break-all">
                    {{ route('telegram.webhook', ['bot' => $bot->id], true) }}
                </code>
                <button onclick="copyToClipboard('{{ route('telegram.webhook', ['bot' => $bot->id], true) }}')" class="px-3 py-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 text-xs font-medium transition-colors flex-shrink-0">
                    Copy
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-600 mb-2">Webhook Secret</p>
            <div class="flex items-center gap-2">
                <code class="flex-1 text-xs bg-gray-50 p-3 rounded-lg text-gray-800 break-all font-mono">
                    {{ $bot->webhook_secret }}
                </code>
                <button onclick="copyToClipboard('{{ $bot->webhook_secret }}')" class="px-3 py-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 text-xs font-medium transition-colors flex-shrink-0">
                    Copy
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-2">Use this as X-Webhook-Secret header</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="flex border-b border-gray-100">
            <button onclick="switchTab('settings')" class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-colors active" data-tab="settings">
                Settings
            </button>
            <button onclick="switchTab('templates')" class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-colors" data-tab="templates">
                Event Templates
            </button>
            <button onclick="switchTab('clients')" class="tab-button px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-colors" data-tab="clients">
                Clients
            </button>
            <a href="{{ route('integrations.index', $bot) }}" class="px-6 py-4 text-sm font-medium text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300 transition-colors">
                Integrations
            </a>
        </div>

        <!-- Settings Tab -->
        <div id="tab-settings" class="tab-content p-6 active">
            <livewire:edit-bot-form :bot="$bot" :key="'edit-'.$bot->id" />
        </div>

        <!-- Templates Tab -->
        <div id="tab-templates" class="tab-content p-6 hidden">
            <livewire:bot-event-templates :bot="$bot" />
        </div>

        <!-- Clients Tab -->
        <div id="tab-clients" class="tab-content p-6 hidden">
            @php
                $clients = $bot->clients()->with('tgUser')->limit(50)->get();
            @endphp
            @include('bots.clients-table', ['clients' => $clients])
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-brand-600', 'text-brand-600', 'active');
            btn.classList.add('border-transparent', 'text-gray-600');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('border-brand-600', 'text-brand-600', 'active');

        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied!');
        });
    }
</script>
@endsection

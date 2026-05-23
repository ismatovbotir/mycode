@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $bot->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Manage bot configuration and settings</p>
        </div>
        <form method="POST" action="{{ route('bots.toggleActive', $bot->uuid) }}" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-4 py-2 rounded-lg border text-sm font-medium transition-colors {{ $bot->is_active ? 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100' : 'bg-gray-50 border-gray-200 text-gray-700 hover:bg-gray-100' }}">
                {{ $bot->is_active ? '✓ Active' : '◯ Inactive' }}
            </button>
        </form>
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
                    {{ route('telegram.webhook', ['bot' => $bot->uuid], true) }}
                </code>
                <button onclick="copyToClipboard('{{ route('telegram.webhook', ['bot' => $bot->uuid], true) }}')" class="px-3 py-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 text-xs font-medium transition-colors flex-shrink-0">
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
        </div>

        <!-- Settings Tab -->
        <div id="tab-settings" class="tab-content p-6 active">
            <form method="POST" action="{{ route('bots.update', $bot->uuid) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Bot Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">Bot Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $bot->name) }}"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                    @error('name')
                        <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Admin Approval -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="requires_admin_approval"
                            value="1"
                            {{ old('requires_admin_approval', $bot->requires_admin_approval) ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-2 focus:ring-brand-500"/>
                        <span class="text-xs font-medium text-blue-900">
                            Require admin approval for client registration
                        </span>
                    </label>
                    <p class="text-xs text-blue-700 mt-1.5">
                        If enabled: new clients must be approved by admin in the Clients section before they can receive messages
                    </p>
                </div>

                <!-- Languages -->
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">Greeting & About (4 Languages)</label>

                    <!-- Language Tabs -->
                    <div class="flex gap-1 mb-4 border-b border-gray-100">
                        @foreach(['uz', 'ru', 'tj', 'kk'] as $lang)
                            <button
                                type="button"
                                onclick="switchLang('{{ $lang }}')"
                                class="lang-tab text-xs px-3 py-2 font-medium transition-colors {{ $loop->first ? 'text-brand-600 border-b-2 border-brand-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600' }} current-lang-uz"
                                data-lang="{{ $lang }}">
                                @switch($lang)
                                    @case('uz')
                                        🇺🇿 O'z
                                        @break
                                    @case('ru')
                                        🇷🇺 Рус
                                        @break
                                    @case('tj')
                                        🇹🇯 Тоҷ
                                        @break
                                    @case('kk')
                                        🏳️ Қар
                                        @break
                                @endswitch
                            </button>
                        @endforeach
                    </div>

                    <!-- Language Content (shown per language via JS) -->
                    @foreach(['uz', 'ru', 'tj', 'kk'] as $lang)
                        <div class="lang-content space-y-3 {{ $loop->first ? '' : 'hidden' }}" data-lang="{{ $lang }}">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Greeting</label>
                                <textarea
                                    name="greeting[{{ $lang }}]"
                                    placeholder="Welcome message..."
                                    rows="2"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old("greeting.$lang", $bot->content['greeting'][$lang] ?? '') }}</textarea>
                                @error("greeting.$lang")
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">About</label>
                                <textarea
                                    name="about[{{ $lang }}]"
                                    placeholder="About this bot..."
                                    rows="2"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old("about.$lang", $bot->content['about'][$lang] ?? '') }}</textarea>
                                @error("about.$lang")
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                        Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Templates Tab -->
        <div id="tab-templates" class="tab-content p-6 hidden">
            <livewire:bot-event-templates :bot="$bot" />
        </div>

        <!-- Clients Tab -->
        <div id="tab-clients" class="tab-content p-6 hidden">
            @include('bots.clients-table', ['clients' => $bot->clients()->with('tgUser')->get()])
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

    function switchLang(lang) {
        document.querySelectorAll('.lang-content').forEach(content => content.classList.add('hidden'));
        document.querySelectorAll('.lang-tab').forEach(tab => {
            tab.classList.remove('text-brand-600', 'border-brand-600');
            tab.classList.add('text-gray-400', 'border-transparent');
        });

        document.querySelector(`[data-lang="${lang}"].lang-content`).classList.remove('hidden');
        document.querySelector(`[data-lang="${lang}"].lang-tab`).classList.remove('text-gray-400', 'border-transparent');
        document.querySelector(`[data-lang="${lang}"].lang-tab`).classList.add('text-brand-600', 'border-brand-600');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied!');
        });
    }
</script>
@endsection

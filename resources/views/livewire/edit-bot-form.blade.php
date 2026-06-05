<form wire:submit="save" class="space-y-6">
    <!-- Bot Name -->
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">Bot Name</label>
        <input
            type="text"
            wire:model="name"
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
                wire:model="requires_admin_approval"
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
        <label class="block text-xs font-medium text-gray-600 mb-2">Greeting & About (3 Languages)</label>

        <!-- Language Tabs -->
        <div class="flex gap-1 mb-4 border-b border-gray-100">
            @foreach(['uz', 'en', 'ru'] as $lang)
                <button
                    type="button"
                    wire:click="switchLang('{{ $lang }}')"
                    class="text-xs px-3 py-2 font-medium transition-colors {{ $currentLang === $lang ? 'text-brand-600 border-b-2 border-brand-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600' }}"
                >
                    @switch($lang)
                        @case('uz')
                            🇺🇿 O'z
                            @break
                        @case('en')
                            🇬🇧 Eng
                            @break
                        @case('ru')
                            🇷🇺 Рус
                            @break
                    @endswitch
                </button>
            @endforeach
        </div>

        <!-- Language Content -->
        @foreach(['uz', 'en', 'ru'] as $lang)
            @if($currentLang === $lang)
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Greeting</label>
                        <textarea
                            wire:model="greeting.{{ $lang }}"
                            placeholder="Welcome message..."
                            rows="2"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
                        @error("greeting.$lang")
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">About</label>
                        <textarea
                            wire:model="about.{{ $lang }}"
                            placeholder="About this bot..."
                            rows="2"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
                        @error("about.$lang")
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Webhook Status -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Telegram Webhook Status</label>
                <div class="flex items-center gap-2">
                    @if($bot->webhook_status === 'success')
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Connected
                        </span>
                    @elseif($bot->webhook_status === 'failed')
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Failed
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded">
                            <svg class="animate-spin w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            @if($bot->webhook_status === 'failed')
                <button
                    type="button"
                    wire:click="retryWebhook"
                    wire:loading.attr="disabled"
                    class="text-xs bg-brand-600 text-white font-medium px-3 py-2 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>🔄 Retry</span>
                    <span wire:loading>
                        <svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Retrying...
                    </span>
                    </button>
            @endif
        </div>
        <p class="text-xs text-gray-500 mt-2">The webhook connects your bot to Telegram for receiving messages</p>
    </div>

    <div class="flex gap-2 pt-4 border-t border-gray-100">
        <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
            Save Changes
        </button>
        <a href="{{ route('bots.show', $bot) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

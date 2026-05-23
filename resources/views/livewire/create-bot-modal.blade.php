<div>
    <!-- Button -->
    <button wire:click="openModal" class="flex items-center gap-2 bg-brand-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Bot
    </button>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                    <h2 class="font-semibold">Create Bot</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="save" class="px-6 py-5 space-y-4">
                    <!-- Bot Name -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Bot Name</label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Store Bot"
                            required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                        @error('name')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telegram Token -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Telegram Token <span class="text-gray-400 font-normal">(@BotFather)</span></label>
                        <input
                            type="text"
                            wire:model="tg_bot_token"
                            placeholder="123456789:AAF..."
                            required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"/>
                        @error('tg_bot_token')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Registration Approval -->
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
                            If enabled: new clients must be approved by admin before they can receive messages
                        </p>
                    </div>

                    <!-- Languages -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2">Greeting & About (4 Languages)</label>

                        <!-- Language Tabs -->
                        <div class="flex gap-1 mb-3 border-b border-gray-100">
                            @foreach(['uz', 'ru', 'tj', 'kk'] as $lang)
                                <button
                                    type="button"
                                    wire:click="switchLang('{{ $lang }}')"
                                    class="lang-tab text-xs px-3 py-2 font-medium transition-colors {{ $currentLang === $lang ? 'text-brand-600 border-b-2 border-brand-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600' }}">
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

                        <!-- Language Content -->
                        <div class="space-y-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Greeting</label>
                                <textarea
                                    wire:model="greeting.{{ $currentLang }}"
                                    placeholder="Greeting..."
                                    rows="2"
                                    required
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
                                @error("greeting.{$currentLang}")
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">About</label>
                                <textarea
                                    wire:model="about.{{ $currentLang }}"
                                    placeholder="About..."
                                    rows="2"
                                    required
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
                                @error("about.{$currentLang}")
                                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-sm text-gray-600 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="text-sm bg-brand-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>Create Bot</span>
                        <span wire:loading>
                            <svg class="animate-spin inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

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

    <div class="flex gap-2 pt-4 border-t border-gray-100">
        <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
            Save Changes
        </button>
        <a href="{{ route('bots.show', $bot) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

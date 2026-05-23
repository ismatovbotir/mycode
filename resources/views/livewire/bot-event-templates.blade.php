<div class="space-y-6">
    <!-- Event Type Selector -->
    <div>
        <label class="block text-xs font-medium text-gray-600 mb-3">Event Type</label>
        <div class="grid grid-cols-2 gap-2 md:grid-cols-3">
            @foreach(\App\Livewire\BotEventTemplates::EVENT_TYPES as $eventType => $label)
                <button
                    type="button"
                    wire:click="switchEvent('{{ $eventType }}')"
                    class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentEvent === $eventType ? 'bg-brand-100 text-brand-700 border border-brand-300' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Language Tabs -->
    <div class="flex gap-1 border-b border-gray-100">
        @foreach(['uz', 'ru', 'tj', 'kk'] as $lang)
            <button
                type="button"
                wire:click="switchLang('{{ $lang }}')"
                class="text-xs px-3 py-2 font-medium transition-colors {{ $currentLang === $lang ? 'text-brand-600 border-b-2 border-brand-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600' }}">
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

    <!-- Template Editor -->
    <div class="space-y-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Message Template</label>
            <textarea
                wire:model.defer="templates.{{ $currentEvent }}.{{ $currentLang }}"
                placeholder="Enter message template..."
                rows="5"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none"></textarea>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
            <p class="text-xs font-medium text-blue-900 mb-1.5">Available Variables:</p>
            <div class="flex flex-wrap gap-2">
                <code class="px-2 py-1 bg-white border border-blue-200 rounded text-xs font-mono text-blue-700">{amount}</code>
                <code class="px-2 py-1 bg-white border border-blue-200 rounded text-xs font-mono text-blue-700">{order_number}</code>
                <code class="px-2 py-1 bg-white border border-blue-200 rounded text-xs font-mono text-blue-700">{date}</code>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div class="flex gap-2">
        <button
            type="button"
            wire:click="saveTemplate('{{ $currentEvent }}')"
            class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
            Save Template
        </button>
    </div>
</div>

@push('scripts')
<script>
    Livewire.on('notify', (data) => {
        alert(data.message || 'Done!');
    });
</script>
@endpush

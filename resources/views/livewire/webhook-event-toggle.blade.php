<div class="space-y-3">
    <h3 class="text-sm font-semibold text-gray-900">Enable Webhook Events</h3>
    <p class="text-xs text-gray-600 mb-4">Choose which events from МойСклад you want to receive and process:</p>

    @if(empty($allEvents))
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
            <p class="font-medium">⚠️ No webhook events configured</p>
            <p class="text-xs mt-1">Contact administrator to add webhook event types</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($allEvents as $event)
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors {{ $event['is_enabled'] ? 'bg-brand-50 border-brand-200' : '' }}">
                    <input
                        type="checkbox"
                        wire:click="toggleEvent('{{ $event['id'] }}')"
                        @checked($event['is_enabled'])
                        class="w-4 h-4 text-brand-600 rounded"
                    />
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $event['icon'] }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $event['name'] }}</span>
                        </div>
                        @if($event['description'])
                            <p class="text-xs text-gray-600 mt-0.5">{{ $event['description'] }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-1 font-mono">{{ $event['event_type'] }}</p>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
            <p><strong>💡 Tip:</strong> Make sure to add the webhook in МойСклад Settings and enable the same events there.</p>
        </div>
    @endif
</div>

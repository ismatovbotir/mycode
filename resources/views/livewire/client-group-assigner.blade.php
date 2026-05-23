<div class="space-y-2">
    @if(count($availableGroups) > 0)
        <div class="space-y-2">
            @foreach($availableGroups as $group)
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model.live="selectedGroups"
                        value="{{ $group['id'] }}"
                        class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-2 focus:ring-brand-500"
                    />
                    <span class="text-xs text-gray-700">{{ $group['name'] }}</span>
                </label>
            @endforeach
        </div>

        @if(count($selectedGroups) > 0 && count($selectedGroups) !== count($availableGroups))
            <button
                wire:click="updateGroups"
                class="w-full px-3 py-1.5 text-xs text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition-colors font-medium mt-2"
            >
                Update Groups
            </button>
        @endif
    @else
        <p class="text-xs text-gray-500">No groups available for this bot. Create a group first.</p>
    @endif
</div>

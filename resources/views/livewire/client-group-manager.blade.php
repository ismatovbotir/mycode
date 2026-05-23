<div class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Client Groups</h3>
        <button
            wire:click="openCreateModal"
            class="px-3 py-1.5 bg-brand-600 text-white text-xs font-medium rounded-lg hover:bg-brand-700 transition-colors"
        >
            + Create Group
        </button>
    </div>

    <!-- Groups List -->
    @if(count($groups) > 0)
        <div class="space-y-2">
            @foreach($groups as $group)
                <div class="bg-gray-50 rounded-lg p-3 flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $group['name'] }}</p>
                        <p class="text-xs text-gray-500">Bot: {{ $group['bot']['name'] ?? 'N/A' }}</p>
                    </div>
                    <button
                        wire:click="deleteGroup('{{ $group['id'] }}')"
                        wire:confirm="Delete this group? Clients will remain but won't be in any group."
                        class="text-xs text-red-600 hover:text-red-700 font-medium"
                    >
                        Delete
                    </button>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-xs text-gray-500">No groups yet. Create one to organize clients.</p>
        </div>
    @endif

    <!-- Create Group Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4">Create Client Group</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Bot</label>
                        <select
                            wire:model="selectedBot"
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        >
                            <option value="">Select a bot</option>
                            @foreach($company->bots as $bot)
                                <option value="{{ $bot->id }}">{{ $bot->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedBot')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Group Name</label>
                        <input
                            type="text"
                            wire:model="newGroupName"
                            placeholder="e.g., VIP Customers, Wholesale, etc."
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        />
                        @error('newGroupName')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-2 mt-6">
                    <button
                        wire:click="createGroup"
                        class="flex-1 px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors"
                    >
                        Create
                    </button>
                    <button
                        wire:click="closeCreateModal"
                        class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

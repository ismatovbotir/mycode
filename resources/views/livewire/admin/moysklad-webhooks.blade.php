<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">МойСклад Webhooks</h2>
        <p class="text-gray-600 mt-1">Manage entity webhooks for CREATE, UPDATE, and DELETE actions</p>
    </div>

    <!-- Success Message -->
    @if(session()->has('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Errors -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            @foreach($errors->all() as $error)
                <p class="text-red-800 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Row 1: Inactive Entities (Available to activate) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">📋</span> Available Entities
                </h3>
                <p class="text-sm text-gray-600 mt-1">Entities without active webhooks</p>
            </div>
            <div class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-semibold">
                {{ $inactiveEntities->count() }}
            </div>
        </div>

        @if($inactiveEntities->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg">✅ All entities are configured with webhooks!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($inactiveEntities as $entity)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $entity->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $entity->type }}</p>
                            </div>
                            <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                {{ $entity->description ?? 'N/A' }}
                            </span>
                        </div>

                        <button
                            wire:click="openActivateModal({{ $entity->id }})"
                            class="w-full mt-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            🔌 Activate Webhooks
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Row 2: Active Webhooks -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">✅</span> Active Webhooks
                </h3>
                <p class="text-sm text-gray-600 mt-1">Entities with configured webhooks</p>
            </div>
            <div class="bg-green-100 text-green-900 px-4 py-2 rounded-full font-semibold">
                {{ $activeEntities->groupBy('entity_id')->count() }}
            </div>
        </div>

        @if($activeEntities->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg">No active webhooks yet. Activate an entity above!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($activeEntities->groupBy('entity_id') as $entityId => $webhooks)
                    @php $entity = $webhooks->first()->entity; @endphp
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $entity->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $entity->type }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                    ✓ ACTIVE (3 webhooks)
                                </span>
                            </div>
                        </div>

                        <!-- Webhook Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                            @foreach(['CREATE', 'UPDATE', 'DELETE'] as $action)
                                @php
                                    $webhook = $webhooks->firstWhere('action', $action);
                                    $icon = match($action) {
                                        'CREATE' => '📝',
                                        'UPDATE' => '✏️',
                                        'DELETE' => '🗑️',
                                        default => '⚙️'
                                    };
                                @endphp
                                <div class="bg-white rounded p-3 border border-green-200">
                                    <p class="text-xs text-gray-600 font-semibold uppercase mb-1">{{ $icon }} {{ $action }}</p>
                                    <code class="text-xs text-gray-800 break-all block mb-2">{{ $webhook?->ms_id ?? 'Not set' }}</code>
                                    <p class="text-xs text-gray-500">Action: {{ $action }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Deactivate Button -->
                        <div class="mt-4">
                            <button
                                wire:click="deactivateEntity({{ $entity->id }})"
                                wire:confirm="Are you sure? This will delete all webhooks from МойСклад."
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                Deactivate All
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Activate Modal -->
    @if($showActivateModal && $selectedEntity)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click="$set('showActivateModal', false)">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">🔌 Activate {{ $selectedEntity->name }}</h3>
                    <button
                        wire:click="$set('showActivateModal', false)"
                        class="text-gray-500 hover:text-gray-700">
                        ✕
                    </button>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    This will create three webhooks on МойСклад for CREATE, UPDATE, and DELETE actions on <strong>{{ $selectedEntity->type }}</strong>.
                </p>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        МойСклад Bearer Token
                    </label>
                    <input
                        type="text"
                        wire:model="selectedToken"
                        placeholder="Enter your МойСклад API token"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                    @error('token')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded p-3 text-xs text-blue-800 mb-4">
                    <p class="font-semibold mb-1">ℹ️ Webhooks that will be created:</p>
                    <ul class="space-y-1">
                        <li>📝 CREATE webhook</li>
                        <li>✏️ UPDATE webhook</li>
                        <li>🗑️ DELETE webhook</li>
                    </ul>
                </div>

                <div class="flex gap-3">
                    <button
                        wire:click="$set('showActivateModal', false)"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button
                        wire:click="activateEntity"
                        wire:loading.attr="disabled"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        <span wire:loading.remove>Activate</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

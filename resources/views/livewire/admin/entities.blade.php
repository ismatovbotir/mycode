<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">МойСклад Entities</h2>
        <p class="text-gray-600 mt-1">Manage your system entities and webhooks</p>
    </div>

    <!-- Row 1: Available Entities (Not yet activated by user) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">📋</span> Available Entities to Activate
                </h3>
                <p class="text-sm text-gray-600 mt-1">Click to activate and create МойСклад webhooks</p>
            </div>
            <div class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-semibold">
                {{ $availableEntities->count() }}
            </div>
        </div>

        @if($availableEntities->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg">✅ All available entities are activated</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableEntities as $entity)
                    <button
                        wire:click="openActivateModal({{ $entity->id }})"
                        class="text-left border border-blue-200 bg-blue-50 rounded-lg p-4 hover:shadow-lg hover:bg-blue-100 transition-all cursor-pointer">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $entity->translations['en'] ?? 'N/A' }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $entity->type }}</p>
                            </div>
                            <span class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded font-medium">
                                Available
                            </span>
                        </div>
                        <p class="text-xs text-gray-600">{{ $entity->messages['en'] ?? 'Entity' }}</p>
                        <div class="mt-3 text-xs text-blue-700 font-semibold">
                            Click to activate →
                        </div>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Row 2: User Entities (Activated by this user) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">✅</span> Your Activated Entities
                </h3>
                <p class="text-sm text-gray-600 mt-1">Entities you have activated with webhooks</p>
            </div>
            <div class="bg-green-100 text-green-900 px-4 py-2 rounded-full font-semibold">
                {{ $userEntities->count() }}
            </div>
        </div>

        @if($userEntities->isEmpty())
            <div class="text-center py-8 text-gray-500">
                <p class="text-lg">No activated entities yet</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($userEntities as $userEntity)
                    <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $userEntity->entity->translations['en'] ?? 'N/A' }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $userEntity->entity->type }}</p>
                            </div>
                            <div class="flex gap-2">
                                <span class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded-full font-semibold">
                                    ✓ ACTIVATED
                                </span>
                            </div>
                        </div>

                        <!-- Webhook ID Info -->
                        @if($userEntity->ms_id)
                            <div class="bg-white rounded p-3 border border-green-200 mb-3">
                                <p class="text-xs text-gray-600 font-semibold uppercase mb-1">🔌 Webhook ID</p>
                                <code class="text-xs text-gray-800 break-all block">{{ $userEntity->ms_id }}</code>
                            </div>
                        @endif

                        <!-- Action Type -->
                        @if($userEntity->action)
                            <div class="mb-3 text-xs text-gray-600">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    Action: {{ $userEntity->action }}
                                </span>
                            </div>
                        @endif

                        <!-- Activated Date -->
                        <div class="text-xs text-gray-500">
                            Activated: {{ $userEntity->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Activate Modal -->
    @if($showActivateModal && $selectedEntityId)
        @php $selectedEntity = \App\Models\Entity::find($selectedEntityId); @endphp
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @if(!$isActivating) wire:click="closeActivateModal" @endif>
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">🔌 Activate Entity</h3>
                    @if(!$isActivating)
                        <button
                            wire:click="closeActivateModal"
                            class="text-gray-500 hover:text-gray-700 text-2xl">
                            ✕
                        </button>
                    @endif
                </div>

                @if($selectedEntity)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-semibold text-gray-900">{{ $selectedEntity->translations['en'] ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-600 mt-1">Type: <code>{{ $selectedEntity->type }}</code></p>
                        <p class="text-xs text-gray-600 mt-1">{{ $selectedEntity->messages['en'] ?? 'Entity' }}</p>
                    </div>

                    @if($isActivating)
                        <!-- Progress Steps -->
                        <div class="mb-4 space-y-2">
                            @foreach($activationSteps as $step)
                                <div class="flex items-start gap-3 p-3 rounded-lg @if($step['status'] === 'completed') bg-green-50 @elseif($step['status'] === 'failed') bg-red-50 @elseif($step['status'] === 'pending' && $loop->index < $currentStep) bg-gray-50 @else bg-blue-50 @endif">
                                    <div class="mt-1 flex-shrink-0">
                                        @if($step['status'] === 'completed')
                                            <span class="text-green-600 font-bold">✓</span>
                                        @elseif($step['status'] === 'failed')
                                            <span class="text-red-600 font-bold">✗</span>
                                        @elseif($loop->index === $currentStep && $isActivating)
                                            <span class="inline-block animate-spin text-blue-600">⟳</span>
                                        @else
                                            <span class="text-gray-400">○</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm @if($step['status'] === 'completed') text-green-800 @elseif($step['status'] === 'failed') text-red-800 @else text-gray-700 @endif">
                                            {{ $step['message'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800">
                                <strong>⚠️ Note:</strong> This will create webhooks on МойСклад for CREATE, UPDATE, and DELETE actions.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button
                                wire:click="closeActivateModal"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-900 font-semibold rounded-lg hover:bg-gray-50">
                                Cancel
                            </button>
                            <button
                                wire:click="activateEntity"
                                class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                                Activate
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>

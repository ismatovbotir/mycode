<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">📊 МойСклад Entities</h2>
        <p class="text-gray-600 mt-1">Manage your МойСклад entities and webhooks</p>
    </div>

    <!-- Token Form (if not set) -->
    @if(!$hasToken)
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900">🔑 Enter МойСклад Token</h3>
                <p class="text-gray-600 mt-1">You need a МойСклад API token to activate entities</p>
            </div>

            <form wire:submit="saveToken" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bearer Token
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        wire:model="bearer_token"
                        placeholder="Paste your МойСклад bearer token"
                        required
                        class="w-full text-sm rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border border-gray-300" />
                    @error('bearer_token')
                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <button
                    type="button"
                    wire:click="testConnection"
                    wire:loading.attr="disabled"
                    class="w-full px-4 py-3 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>🔗 Test Connection</span>
                    <span wire:loading>Testing...</span>
                </button>

                @isset($test_message)
                    @if($test_message)
                        <div class="p-4 rounded-lg {{ $test_passed ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }} text-sm font-medium">
                            {{ $test_message }}
                        </div>
                    @endif
                @endisset

                @error('test')
                    <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                        {{ $message }}
                    </div>
                @enderror

                @isset($test_passed)
                    @if($test_passed)
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove>✓ Save Token</span>
                            <span wire:loading>Saving...</span>
                        </button>
                    @else
                        <button
                            type="button"
                            disabled
                            class="w-full px-4 py-3 bg-gray-200 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                            ✓ Save Token (Test first)
                        </button>
                    @endif
                @endisset
            </form>
        </div>
    @else
        <!-- Entities List -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">📋 All Entities</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage your МойСклад entities</p>
                </div>
                <div class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-semibold">
                    {{ $allEntities->count() }}
                </div>
            </div>

            @if($allEntities->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <p class="text-lg">No entities available</p>
                </div>
            @else
                <div class="space-y-3">
                    @isset($allEntities, $userEntities)
                        @php
                            // Separate activated and not activated
                            $activated = $allEntities->filter(function($entity) use ($userEntities) {
                                return $userEntities->where('entity_id', $entity->id)->count() > 0;
                            });
                            $notActivated = $allEntities->filter(function($entity) use ($userEntities) {
                                return $userEntities->where('entity_id', $entity->id)->count() === 0;
                            });
                        @endphp

                    <!-- Activated Entities -->
                    @foreach($activated as $entity)
                        @php
                            $entityActions = $userEntities->where('entity_id', $entity->id);
                        @endphp
                        <div class="p-4 border border-green-200 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $entity->translations['en'] ?? 'N/A' }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $entity->type }}</p>
                                </div>
                            </div>

                            <!-- Webhook IDs for each action -->
                            <div class="space-y-3">
                                @foreach(['CREATE', 'UPDATE', 'DELETE'] as $action)
                                    @php
                                        $actionRecord = $entityActions->where('action', $action)->first();
                                    @endphp
                                    <div class="border border-gray-200 rounded p-2 bg-white">
                                        <div class="flex items-center justify-between gap-2 text-xs mb-2">
                                            <div class="flex items-center gap-2 flex-1">
                                                <span class="font-semibold text-gray-700 w-16">{{ $action }}:</span>
                                                @if($actionRecord && !empty($actionRecord->ms_id))
                                                    <span class="text-green-700 font-semibold">✓</span>
                                                    <code class="text-gray-600 bg-gray-100 px-2 py-1 rounded flex-1 break-all">{{ $actionRecord->ms_id }}</code>
                                                @elseif($actionRecord)
                                                    <span class="text-red-700 font-semibold">✗</span>
                                                    <span class="text-red-500">Failed</span>
                                                @else
                                                    <span class="text-yellow-700 font-semibold">⏳</span>
                                                    <span class="text-gray-500">Pending...</span>
                                                @endif
                                            </div>
                                            @if($actionRecord && empty($actionRecord->ms_id))
                                                <button
                                                    wire:click="openRetryModal('{{ $actionRecord->id }}')"
                                                    class="px-2 py-1 bg-blue-500 text-white rounded text-xs font-semibold hover:bg-blue-600 whitespace-nowrap">
                                                    🔄 Retry
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Error Message -->
                                        @if($actionRecord)
                                            @php
                                                $messages = $actionRecord->messages;
                                                $error = $messages['error'] ?? null;
                                            @endphp
                                            @if($error && empty($actionRecord->ms_id))
                                                <div class="mb-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                                                    <span class="font-semibold block">⚠️ Error:</span>
                                                    <span class="block mt-1 break-words">{{ $error }}</span>
                                                </div>
                                            @endif
                                        @endif

                                        <!-- Request Body -->
                                        @if($actionRecord)
                                            @php
                                                $messages = $actionRecord->messages;
                                                $requestBody = $messages['request_body'] ?? $messages['webhook_response']['request_body'] ?? null;
                                            @endphp
                                            @if($requestBody)
                                                <details class="text-xs">
                                                    <summary class="cursor-pointer font-semibold text-gray-600 hover:text-gray-900">📤 Request Body</summary>
                                                    <div class="mt-2 bg-gray-900 text-gray-100 p-2 rounded font-mono text-xs overflow-x-auto">
                                                        <pre>{{ json_encode($requestBody, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    </div>
                                                </details>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Not Activated Entities -->
                    @foreach($notActivated as $entity)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $entity->translations['en'] ?? 'N/A' }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $entity->type }}</p>
                            </div>

                            <!-- Activate Button -->
                            <button
                                wire:click="activateEntity({{ $entity->id }})"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                                <span wire:loading.remove>🔌 Activate</span>
                                <span wire:loading>⟳ Activating...</span>
                            </button>
                        </div>
                    @endforeach
                    @endisset
                </div>
            @endif
        </div>
    @endif

    <!-- Retry Modal -->
    @if($showRetryModal && $retryingUserEntityId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-data="{ countdown: 10 }" x-init="setInterval(() => { countdown--; if(countdown <= 0) { $wire.closeRetryModal() } }, 1000)">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                <!-- Header -->
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">🔄 Retrying Webhook</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $retryCommand }} Action</p>
                </div>

                <!-- Request Details -->
                <div class="space-y-4 mb-6">
                    <!-- URL -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">URL</label>
                        <div class="bg-gray-100 rounded p-3 text-xs text-gray-700 break-all font-mono">
                            {{ $retryUrl }}
                        </div>
                    </div>

                    <!-- Payload -->
                    @if($retryPayload)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Request Body</label>
                            <div class="bg-gray-900 text-gray-100 rounded p-3 font-mono text-xs overflow-x-auto">
                                <pre>{{ json_encode($retryPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Response or Loading -->
                @if($retryResponse)
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-2">МойСклад Response</label>
                        <div class="@if($retryResponse['success']) bg-green-50 border border-green-200 @else bg-red-50 border border-red-200 @endif rounded p-3 text-xs">
                            @if($retryResponse['success'])
                                <p class="text-green-800 font-semibold mb-2">✓ Webhook Created Successfully!</p>
                            @else
                                <p class="text-red-800 font-semibold mb-2">✗ Error Creating Webhook</p>
                            @endif
                            <div class="bg-gray-900 text-gray-100 rounded p-2 font-mono text-xs overflow-x-auto">
                                <pre>{{ json_encode($retryResponse['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Loading Animation -->
                    <div class="text-center">
                        <div class="flex justify-center mb-4">
                            <svg class="animate-spin h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 font-semibold">Creating webhook...</p>
                        <p class="text-xs text-gray-500 mt-2">Auto-closing in <span x-text="countdown">10</span>s</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>

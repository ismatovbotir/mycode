<form wire:submit="save" class="space-y-4">
    <!-- Bearer Token Field -->
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
            class="w-full text-sm rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('bearer_token') border-2 border-red-500 @else border border-gray-300 @enderror"/>

        @error('bearer_token')
            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Test Button -->
    <button
        type="button"
        wire:click="testConnection"
        wire:loading.attr="disabled"
        class="w-full px-4 py-3 bg-blue-100 text-blue-700 font-semibold rounded-lg hover:bg-blue-200 transition-colors disabled:opacity-50 mt-6">
        <span wire:loading.remove>🔗 Test Connection</span>
        <span wire:loading>
            <svg class="animate-spin inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Testing...
        </span>
    </button>

    <!-- Test Result -->
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

    <!-- Response Details Link -->
    @isset($showModal, $requestUrl)
        @if($showModal && $requestUrl)
            <button
                type="button"
                wire:click="$toggle('showModal')"
                class="w-full px-4 py-2 text-sm text-gray-600 hover:text-blue-600 underline">
                📋 View Response Details
            </button>
        @endif
    @endisset

    <!-- Save Button -->
    @isset($test_passed)
        @if($test_passed)
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                <span wire:loading.remove>✓ Save & Continue</span>
                <span wire:loading>
                    <svg class="animate-spin inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Saving...
                </span>
            </button>
        @else
            <button
                type="button"
                disabled
                class="w-full px-4 py-3 bg-gray-200 text-gray-600 font-semibold rounded-lg cursor-not-allowed">
                ✓ Save & Continue (Test first)
            </button>
        @endif
    @endisset
</form>

<!-- Response Details Modal -->
@isset($showModal, $requestUrl, $responseCode, $requestMethod, $requestHeaders, $responseBody)
@if($showModal && $requestUrl)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" wire:click="closeModal">
        <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" wire:click.stop>
            <!-- Modal Header -->
            <div class="sticky top-0 bg-gradient-to-r {{ $responseCode === 200 ? 'from-green-600 to-green-700' : 'from-red-600 to-red-700' }} px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">📡 API Request & Response Details</h3>
                <button
                    type="button"
                    wire:click="closeModal"
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded-lg p-1">
                    ✕
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-6">
                <!-- Request Section Header -->
                <div class="border-b-2 border-blue-200 pb-2">
                    <h4 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                        📤 Request Details
                    </h4>
                </div>

                <!-- Request Method -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">🔹 HTTP Method</label>
                    <div class="inline-block bg-blue-100 text-blue-900 px-4 py-2 rounded-lg font-mono font-bold text-lg">
                        {{ $requestMethod }}
                    </div>
                </div>

                <!-- Request URL -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">📍 Request URL</label>
                    <div class="bg-gray-100 rounded-lg p-4 font-mono text-sm text-gray-700 break-all border border-gray-200">
                        {{ $requestUrl }}
                    </div>
                </div>

                <!-- Request Headers -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">📋 Request Headers</label>
                    <div class="bg-gray-900 rounded-lg p-4 font-mono text-xs text-gray-100 overflow-x-auto border border-gray-700" style="max-height: 250px;">
                        <pre>@foreach($requestHeaders as $header => $value){{ $header }}: {{ $value }}
@endforeach</pre>
                    </div>
                </div>

                <!-- Response Section Header -->
                <div class="border-b-2 border-green-200 pb-2 pt-4">
                    <h4 class="text-lg font-bold text-green-900 flex items-center gap-2">
                        📥 Response Details
                    </h4>
                </div>
                <!-- Status Badge -->
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $responseCode === 200 ? '✅' : '⚠️' }}</span>
                    <div>
                        <p class="text-sm text-gray-600">HTTP Status Code</p>
                        <p class="text-2xl font-bold {{ $responseCode === 200 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $responseCode }}
                        </p>
                    </div>
                </div>

                <!-- Status Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">📋 Status Description</label>
                    <div class="bg-gray-100 rounded-lg p-4 text-sm text-gray-700 border border-gray-200">
                        @switch($responseCode)
                            @case(200)
                                <span class="text-green-700 font-semibold">✓ OK - Connection successful!</span>
                                <p class="text-gray-600 mt-1">Your bearer token is valid and the API is accessible.</p>
                                @break
                            @case(401)
                                <span class="text-red-700 font-semibold">✗ Unauthorized</span>
                                <p class="text-gray-600 mt-1">Invalid or expired bearer token. Please check your token.</p>
                                @break
                            @case(403)
                                <span class="text-red-700 font-semibold">✗ Forbidden</span>
                                <p class="text-gray-600 mt-1">Access denied. Your token may not have required permissions.</p>
                                @break
                            @case(404)
                                <span class="text-red-700 font-semibold">✗ Not Found</span>
                                <p class="text-gray-600 mt-1">API endpoint not found. Please check the URL.</p>
                                @break
                            @case(500)
                                <span class="text-red-700 font-semibold">✗ Server Error</span>
                                <p class="text-gray-600 mt-1">МойСклад API server error. Try again later.</p>
                                @break
                            @case(0)
                                <span class="text-red-700 font-semibold">✗ Connection Error</span>
                                <p class="text-gray-600 mt-1">Could not connect to the API. Check your internet connection.</p>
                                @break
                            @default
                                <span class="text-orange-700 font-semibold">⚠ HTTP {{ $responseCode }}</span>
                                <p class="text-gray-600 mt-1">Unexpected status code received from the API.</p>
                        @endswitch
                    </div>
                </div>

                <!-- Response Body -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">📦 Response Body</label>
                    <div class="bg-gray-900 rounded-lg p-4 font-mono text-xs text-gray-100 overflow-x-auto border border-gray-700" style="max-height: 300px;">
                        <pre>{{ $responseBody }}</pre>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    @if($responseCode === 200)
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                            ✓ Connection OK - Close
                        </button>
                    @else
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="flex-1 px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                            Close
                        </button>
                        <a
                            href="https://support.moysklad.ru"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors text-center">
                            📖 Get Help
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endisset

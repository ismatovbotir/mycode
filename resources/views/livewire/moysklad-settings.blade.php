<div>
    <!-- МойСклад Integration Section -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold">📊 МойСклад Bearer Token</h2>
            <p class="text-sm text-gray-600 mt-1">
                @if(auth()->user()->moysklad_token)
                    Your МойСклад account is connected
                @else
                    Connect your МойСклад account
                @endif
            </p>
        </div>

        <form wire:submit="save" class="px-6 py-5 space-y-4">
            <!-- Status -->
            @if(auth()->user()->moysklad_token)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <p class="text-sm text-green-800 font-medium">✓ Connected</p>
                    <p class="text-xs text-green-700 mt-1">Bearer token is saved and active</p>
                </div>
            @endif

            <!-- Bearer Token Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bearer Token
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="password"
                    wire:model="bearer_token"
                    placeholder="Paste your МойСклад bearer token"
                    required
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 @error('bearer_token') border-red-500 @enderror"/>

                <p class="text-xs text-gray-500 mt-2">Get from МойСклад Settings → API → Create Token</p>

                @error('bearer_token')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Test Connection Button -->
            <div class="border-t border-gray-100 pt-4">
                <div class="flex gap-2 mb-4">
                    <button
                        type="button"
                        wire:click="testConnection"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>🔗 Test Connection</span>
                        <span wire:loading>
                            <svg class="animate-spin inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Testing...
                        </span>
                    </button>
                </div>

                <!-- Test Result -->
                @if($test_message)
                    <div class="p-4 rounded-lg {{ $test_passed ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }} text-sm font-medium mb-4">
                        {{ $test_message }}
                    </div>
                @endif

                @error('test')
                    <div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-medium mb-4">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Submit Button -->
                @if($test_passed)
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-brand-600 text-white font-medium rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>💾 Save Token</span>
                        <span wire:loading>
                            <svg class="animate-spin inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Saving...
                        </span>
                    </button>
                @else
                    <button
                        type="button"
                        disabled
                        class="px-4 py-2 bg-gray-200 text-gray-600 font-medium rounded-lg cursor-not-allowed">
                        💾 Save Token (Test first)
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

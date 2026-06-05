<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-2">Telegram Webhook Tester</h1>
    <p class="text-gray-600 mb-6">Enter your bot token and manage webhooks</p>

    <!-- Token Input Section -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex gap-2">
            <input
                type="password"
                wire:model="tg_bot_token"
                placeholder="Enter Telegram bot token (from @BotFather)"
                class="flex-1 text-sm border border-gray-200 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"/>
            <button
                type="button"
                wire:click="verifyToken"
                wire:loading.attr="disabled"
                class="bg-brand-600 text-white font-medium px-6 py-3 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                <span wire:loading.remove>Get Info</span>
                <span wire:loading><svg class="animate-spin inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
            </button>
        </div>
        @if($verificationError)
            <p class="text-red-600 text-xs mt-2">{{ $verificationError }}</p>
        @endif
    </div>

    @if($tokenVerified && $botInfo)
        <!-- Bot Info Card -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Bot ID</p>
                    <p class="text-sm font-mono text-green-900">{{ $botInfo['id'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Username</p>
                    <p class="text-sm font-mono text-green-900">@{{ $botInfo['username'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Name</p>
                    <p class="text-sm font-mono text-green-900">{{ $botInfo['first_name'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">Can Join Groups</p>
                    <p class="text-sm font-mono text-green-900">{{ $botInfo['can_join_groups'] ? '✓ Yes' : '✗ No' }}</p>
                </div>
            </div>

            <!-- Bot UUID Display -->
            @if($botUuid)
                <div class="mb-4">
                    <p class="text-xs text-gray-600 font-medium mb-1.5">Bot UUID (Database ID)</p>
                    <p class="text-xs font-mono text-green-900 bg-green-100 p-2 rounded-lg break-all">{{ $botUuid }}</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-2 flex-wrap">
                <button
                    type="button"
                    wire:click="setWebhook"
                    wire:loading.attr="disabled"
                    class="bg-blue-600 text-white font-medium text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>🔗 Set Webhook</span>
                    <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                </button>
                <button
                    type="button"
                    wire:click="getWebhookInfo"
                    wire:loading.attr="disabled"
                    class="bg-green-600 text-white font-medium text-sm px-4 py-2 rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>ℹ️ Get Info</span>
                    <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                </button>
                <button
                    type="button"
                    wire:click="deleteWebhook"
                    wire:loading.attr="disabled"
                    class="bg-red-600 text-white font-medium text-sm px-4 py-2 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>🗑️ Delete</span>
                    <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                </button>
            </div>
        </div>

        <!-- Webhook Info Card -->
        @if($webhookInfo)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                <h3 class="font-semibold text-blue-900 mb-4">Webhook Information</h3>
                <div class="space-y-3 text-sm">
                    @if($webhookInfo['url'] ?? false)
                        <div>
                            <p class="text-xs font-medium text-gray-600">URL</p>
                            <p class="text-xs font-mono text-blue-900 break-all">{{ $webhookInfo['url'] }}</p>
                        </div>
                    @else
                        <p class="text-blue-700 font-medium">No webhook configured</p>
                    @endif

                    @if(isset($webhookInfo['pending_update_count']))
                        <div>
                            <p class="text-xs font-medium text-gray-600">Pending Updates</p>
                            <p class="text-xs font-mono text-blue-900">{{ $webhookInfo['pending_update_count'] }}</p>
                        </div>
                    @endif

                    @if(isset($webhookInfo['last_error_date']) && $webhookInfo['last_error_date'])
                        <div>
                            <p class="text-xs font-medium text-gray-600">Last Error</p>
                            <p class="text-xs font-mono text-red-700">{{ date('Y-m-d H:i:s', $webhookInfo['last_error_date']) }}</p>
                            @if($webhookInfo['last_error_message'] ?? false)
                                <p class="text-xs font-mono text-red-600 mt-1">{{ $webhookInfo['last_error_message'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Messages -->
        @if($webhookMessage)
            <div class="rounded-lg p-4 mb-6 {{
                $webhookMessageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
                ($webhookMessageType === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-blue-50 border border-blue-200 text-blue-700')
            }}">
                <p class="font-medium">{{ $webhookMessage }}</p>
            </div>
        @endif

        <!-- Response Playground -->
        @if($currentResponse)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">
                    {{ $lastAction }} Response
                </h3>
                <div class="bg-gray-900 rounded-lg p-4 font-mono text-xs text-gray-100 overflow-x-auto">
                    <pre>{{ json_encode($currentResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </div>
        @endif
    @endif
</div>

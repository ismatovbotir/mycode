<div>
    <!-- Button -->
    <button wire:click="openModal" class="flex items-center gap-2 bg-brand-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Bot
    </button>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                    <h2 class="font-semibold">Create Bot</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="save" class="px-6 py-5 space-y-4">
                    <!-- Bot Name -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Bot Name</label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Store Bot"
                            required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                        @error('name')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telegram Token -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Telegram Token <span class="text-gray-400 font-normal">(@BotFather)</span></label>
                        <div class="flex gap-2">
                            <input
                                type="text"
                                wire:model="tg_bot_token"
                                placeholder="123456789:AAF..."
                                required
                                {{ $tokenVerified ? 'disabled' : '' }}
                                class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono {{ $tokenVerified ? 'bg-gray-50' : '' }}"/>
                            <button
                                type="button"
                                wire:click="verifyToken"
                                wire:loading.attr="disabled"
                                {{ $tokenVerified ? 'disabled' : '' }}
                                class="text-sm bg-brand-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50 whitespace-nowrap">
                                <span wire:loading.remove>
                                    @if($tokenVerified)
                                        ✓ Verified
                                    @else
                                        Verify
                                    @endif
                                </span>
                                <span wire:loading>
                                    <svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </span>
                            </button>
                        </div>
                        @if($verificationError)
                            <span class="text-xs text-red-600 mt-1 block">{{ $verificationError }}</span>
                        @endif
                        @error('tg_bot_token')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Bot Info (after verification) -->
                    @if($tokenVerified && $botInfo)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-green-900 text-sm mb-2">✓ Bot verified successfully</h4>
                                    <div class="text-xs text-green-700 space-y-1 mb-3">
                                        <p><strong>Bot ID:</strong> {{ $botInfo['id'] ?? 'N/A' }}</p>
                                        <p><strong>Bot Name:</strong> @{{ $botInfo['username'] ?? 'N/A' }}</p>
                                        <p><strong>Display Name:</strong> {{ $botInfo['first_name'] ?? 'N/A' }}</p>
                                        <p><strong>Can Join Groups:</strong> {{ $botInfo['can_join_groups'] ? '✓ Yes' : '✗ No' }}</p>
                                    </div>

                                    <!-- Webhook Actions -->
                                    <div class="flex gap-2 mt-3 pt-3 border-t border-green-200 flex-wrap">
                                        <button
                                            type="button"
                                            wire:click="setWebhook"
                                            wire:loading.attr="disabled"
                                            class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 transition-colors disabled:opacity-50 font-medium">
                                            <span wire:loading.remove>🔗 Set Webhook</span>
                                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="getWebhookInfo"
                                            wire:loading.attr="disabled"
                                            class="text-xs bg-green-600 text-white px-3 py-1.5 rounded hover:bg-green-700 transition-colors disabled:opacity-50 font-medium">
                                            <span wire:loading.remove>ℹ️ Get Info</span>
                                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="deleteWebhook"
                                            wire:loading.attr="disabled"
                                            class="text-xs bg-red-600 text-white px-3 py-1.5 rounded hover:bg-red-700 transition-colors disabled:opacity-50 font-medium">
                                            <span wire:loading.remove>🗑️ Delete</span>
                                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Webhook Info Card -->
                        @if($webhookInfo)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h4 class="font-semibold text-blue-900 text-sm mb-3">🔗 Webhook Information</h4>
                                <div class="space-y-2 text-xs text-blue-800">
                                    @if($webhookInfo['url'] ?? false)
                                        <p><strong>URL:</strong> <code class="bg-blue-100 px-1 rounded break-all">{{ $webhookInfo['url'] }}</code></p>
                                    @else
                                        <p class="text-blue-600"><strong>Status:</strong> No webhook configured</p>
                                    @endif

                                    @if(isset($webhookInfo['has_custom_certificate']))
                                        <p><strong>Custom Certificate:</strong> {{ $webhookInfo['has_custom_certificate'] ? '✓ Yes' : '✗ No' }}</p>
                                    @endif

                                    @if(isset($webhookInfo['pending_update_count']))
                                        <p><strong>Pending Updates:</strong> {{ $webhookInfo['pending_update_count'] }}</p>
                                    @endif

                                    @if(isset($webhookInfo['last_error_date']))
                                        <p><strong>Last Error:</strong> {{ $webhookInfo['last_error_date'] ? date('Y-m-d H:i:s', $webhookInfo['last_error_date']) : 'None' }}</p>
                                    @endif

                                    @if(isset($webhookInfo['last_error_message']))
                                        <p><strong>Error Message:</strong> {{ $webhookInfo['last_error_message'] ?? 'None' }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Webhook Messages -->
                        @if($webhookMessage)
                            <div class="rounded-lg p-3 {{
                                $webhookMessageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
                                ($webhookMessageType === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-blue-50 border border-blue-200 text-blue-700')
                            }}">
                                <p class="text-xs font-medium">{{ $webhookMessage }}</p>
                            </div>
                        @endif
                    @endif

                    <!-- Registration Approval -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                wire:model="requires_admin_approval"
                                class="w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-2 focus:ring-brand-500"/>
                            <span class="text-xs font-medium text-blue-900">
                                Require admin approval for client registration
                            </span>
                        </label>
                        <p class="text-xs text-blue-700 mt-1.5">
                            If enabled: new clients must be approved by admin before they can receive messages
                        </p>
                    </div>

                    <!-- Languages -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2">Greeting & About (3 Languages)</label>

                        <!-- Language Tabs -->
                        <div class="flex gap-1 mb-3 border-b border-gray-100 overflow-x-auto">
                            @foreach(['uz', 'en', 'ru'] as $lang)
                                <button
                                    type="button"
                                    wire:click="switchLang('{{ $lang }}')"
                                    class="lang-tab text-xs px-3 py-2 font-medium transition-colors whitespace-nowrap {{ $currentLang === $lang ? 'text-brand-600 border-b-2 border-brand-600' : 'text-gray-400 border-b-2 border-transparent hover:text-gray-600' }}">
                                    @switch($lang)
                                        @case('uz')
                                            🇺🇿 O'z
                                            @break
                                        @case('en')
                                            🇬🇧 Eng
                                            @break
                                        @case('ru')
                                            🇷🇺 Рус
                                            @break
                                    @endswitch
                                </button>
                            @endforeach
                        </div>

                        <!-- Language Content -->
                        <div class="space-y-2">
                            @php
                                $samples = [
                                    'uz' => [
                                        'greeting' => "Assalomu alaikum! 👋\n\nBo'timizga xush kelibsiz. Men sizning savollarga javob berishga va kerakli ma'lumotlarni berish uchun tayyorman.",
                                        'about' => "🤖 Avtomatik xizmat boti\n\n✅ Tez javoblar\n✅ 24/7 mavjud\n✅ Oson foydalanish\n\nBizning bilan bog'lanish uchun /start bosing."
                                    ],
                                    'en' => [
                                        'greeting' => "Welcome! 👋\n\nHello and welcome to our bot. I'm ready to answer your questions and provide all the necessary information.",
                                        'about' => "🤖 Automated Support Bot\n\n✅ Quick responses\n✅ Available 24/7\n✅ Easy to use\n\nPress /start to begin."
                                    ],
                                    'ru' => [
                                        'greeting' => "Добро пожаловать! 👋\n\nВы попали на нашего помощника. Я готов ответить на ваши вопросы и предоставить необходимую информацию.",
                                        'about' => "🤖 Автоматический бот поддержки\n\n✅ Быстрые ответы\n✅ Доступен 24/7\n✅ Легко использовать\n\nНажмите /start чтобы начать."
                                    ]
                                ];
                            @endphp

                            @foreach(['uz', 'en', 'ru'] as $lang)
                                @if($currentLang === $lang)
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-medium text-gray-600">Greeting</label>
                                            <button type="button" wire:click="$set('greeting.{{ $lang }}', '{{ addslashes($samples[$lang]['greeting']) }}')" class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                                                Load Sample
                                            </button>
                                        </div>
                                        <textarea
                                            wire:model.live="greeting.{{ $lang }}"
                                            rows="4"
                                            required
                                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none overflow-y-auto max-h-32"></textarea>
                                        <p class="text-xs text-gray-400 mt-1">Sample: {{ substr($samples[$lang]['greeting'], 0, 60) }}...</p>
                                        @error("greeting.{$lang}")
                                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-xs font-medium text-gray-600">About</label>
                                            <button type="button" wire:click="$set('about.{{ $lang }}', '{{ addslashes($samples[$lang]['about']) }}')" class="text-xs text-brand-600 hover:text-brand-700 font-medium">
                                                Load Sample
                                            </button>
                                        </div>
                                        <textarea
                                            wire:model.live="about.{{ $lang }}"
                                            rows="4"
                                            required
                                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none overflow-y-auto max-h-32"></textarea>
                                        <p class="text-xs text-gray-400 mt-1">Sample: {{ substr($samples[$lang]['about'], 0, 60) }}...</p>
                                        @error("about.{$lang}")
                                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-sm text-gray-600 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        {{ !$tokenVerified ? 'disabled' : '' }}
                        class="text-sm bg-brand-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>
                            @if(!$tokenVerified)
                                Verify token first
                            @else
                                Create Bot
                            @endif
                        </span>
                        <span wire:loading>
                            <svg class="animate-spin inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Webhook Configuration Modal -->
    @if($showWebhookModal && $createdBot)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click="closeWebhookModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                    <div>
                        <h2 class="font-semibold">✅ Bot Created Successfully!</h2>
                        <p class="text-xs text-gray-500 mt-1">Your Telegram bot webhook is configured</p>
                    </div>
                    <button wire:click="closeWebhookModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-5 space-y-5">
                    <!-- Bot Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 text-sm mb-2">🤖 Bot Created Successfully</h3>
                        <div class="text-xs text-blue-700 space-y-1">
                            <p><strong>Bot ID:</strong> {{ $createdBot->tg_bot_id }}</p>
                            <p><strong>Bot Name:</strong> @{{ $createdBot->tg_username }}</p>
                            <p><strong>Display Name:</strong> {{ $createdBot->tg_first_name }}</p>
                        </div>
                    </div>

                    <!-- Webhook Status -->
                    @if($createdBot->webhook_status)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-green-900 text-sm">✅ Webhook Active</h3>
                                    <p class="text-xs text-green-700 mt-1">Your bot webhook is configured and ready to receive updates.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-amber-900 text-sm">⏳ Webhook Not Configured</h3>
                                    <p class="text-xs text-amber-700 mt-1">Configure webhook using the buttons below.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Webhook URL -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2">Webhook URL</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 font-mono text-xs text-gray-700 overflow-x-auto break-all">
                            {{ route('telegram.webhook', ['bot' => $createdBot->id], true) }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">This is the endpoint that receives updates from Telegram</p>
                    </div>

                    <!-- Webhook Actions -->
                    <div class="flex gap-2 flex-wrap">
                        <button
                            type="button"
                            wire:click="setWebhook"
                            wire:loading.attr="disabled"
                            class="text-xs bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 font-medium">
                            <span wire:loading.remove>🔗 Set Webhook</span>
                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                        </button>
                        <button
                            type="button"
                            wire:click="getWebhookInfo"
                            wire:loading.attr="disabled"
                            class="text-xs bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 font-medium">
                            <span wire:loading.remove>ℹ️ Get Info</span>
                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                        </button>
                        <button
                            type="button"
                            wire:click="deleteWebhook"
                            wire:loading.attr="disabled"
                            class="text-xs bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50 font-medium">
                            <span wire:loading.remove>🗑️ Delete</span>
                            <span wire:loading><svg class="animate-spin inline w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                        </button>
                    </div>

                    <!-- Webhook Info Card -->
                    @if($webhookInfo)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 text-sm mb-3">🔗 Webhook Information</h4>
                            <div class="space-y-2 text-xs text-blue-800">
                                @if($webhookInfo['url'] ?? false)
                                    <p><strong>URL:</strong> <code class="bg-blue-100 px-1 rounded break-all">{{ $webhookInfo['url'] }}</code></p>
                                @else
                                    <p class="text-blue-600"><strong>Status:</strong> No webhook configured</p>
                                @endif

                                @if(isset($webhookInfo['pending_update_count']))
                                    <p><strong>Pending Updates:</strong> {{ $webhookInfo['pending_update_count'] }}</p>
                                @endif

                                @if(isset($webhookInfo['last_error_date']))
                                    <p><strong>Last Error:</strong> {{ $webhookInfo['last_error_date'] ? date('Y-m-d H:i:s', $webhookInfo['last_error_date']) : 'None' }}</p>
                                @endif

                                @if(isset($webhookInfo['last_error_message']))
                                    <p><strong>Error Message:</strong> {{ $webhookInfo['last_error_message'] ?? 'None' }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Webhook Messages -->
                    @if($webhookMessage)
                        <div class="rounded-lg p-3 {{
                            $webhookMessageType === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
                            ($webhookMessageType === 'error' ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-blue-50 border border-blue-200 text-blue-700')
                        }}">
                            <p class="text-xs font-medium">{{ $webhookMessage }}</p>
                        </div>
                    @endif

                    <!-- Response Format -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2">Response Format</label>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-xs text-blue-900 mb-2 font-medium">Expected JSON response on successful event processing:</p>
                            <pre class="bg-gray-900 text-gray-100 text-xs p-3 rounded overflow-x-auto"><code>{
  "status": "received",
  "eventType": "entity.demand.create"
}</code></pre>
                            <p class="text-xs text-blue-700 mt-2">Status codes:</p>
                            <ul class="text-xs text-blue-700 mt-1 space-y-1 list-disc list-inside">
                                <li><strong>200 OK</strong> - Event processed successfully</li>
                                <li><strong>403 Forbidden</strong> - Invalid webhook secret</li>
                                <li><strong>400 Bad Request</strong> - Missing or invalid eventType</li>
                                <li><strong>500 Server Error</strong> - Processing error</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <p class="text-xs text-amber-900">
                            <strong>ℹ️ Note:</strong> Your webhook is already configured in Telegram. Messages from МойСклад events will be automatically processed and sent to your users.
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closeWebhookModal"
                        class="text-sm bg-brand-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">
                        Got it! Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

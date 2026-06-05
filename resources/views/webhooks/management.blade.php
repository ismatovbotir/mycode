@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Webhook Management</h1>
        <p class="text-sm text-gray-500 mt-1">Bot: <strong>{{ $bot->name }}</strong></p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Setup Instructions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-6">
                <h2 class="text-lg font-semibold mb-4">📋 Setup Guide</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-2">1. Webhook URL</p>
                        <div class="flex gap-2">
                            <code class="flex-1 text-xs bg-gray-50 p-2 rounded border border-gray-200 break-all text-gray-800">
                                {{ $webhookUrl }}
                            </code>
                            <button
                                onclick="copyToClipboard('{{ $webhookUrl }}')"
                                class="px-2 py-1 bg-brand-600 text-white rounded text-xs font-medium hover:bg-brand-700 transition-colors flex-shrink-0"
                            >
                                Copy
                            </button>
                        </div>
                    </div>

                    @if($integration)
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs font-medium text-green-700 mb-2">✓ Integration Connected</p>
                            <p class="text-xs text-gray-600">
                                МойСклад integration is active. Webhooks will be processed automatically.
                            </p>
                        </div>
                    @else
                        <div class="pt-4 border-t border-gray-200">
                            <p class="text-xs font-medium text-yellow-700 mb-2">⚠️ No Integration</p>
                            <p class="text-xs text-gray-600 mb-3">
                                Add a МойСклад integration first to process webhooks.
                            </p>
                            <a href="{{ route('integrations.index', $bot) }}" class="text-xs text-brand-600 hover:underline font-medium">
                                Add Integration →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Setup Instructions + Events -->
        <div class="lg:col-span-2 space-y-6">
            <!-- МойСклад Setup Instructions -->
            <div class="bg-white rounded-xl border border-blue-200 p-6">
                <h2 class="text-lg font-semibold mb-4 text-blue-900">🔧 How to Add Webhook in МойСклад</h2>

                <div class="space-y-4">
                    <ol class="space-y-3 text-sm">
                        <li class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium text-xs">1</span>
                            <div>
                                <p class="font-medium text-gray-900">Go to МойСклад Settings</p>
                                <p class="text-xs text-gray-600">Log in to your МойСклад account and navigate to Settings → API → Webhooks</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium text-xs">2</span>
                            <div>
                                <p class="font-medium text-gray-900">Click "Add Webhook"</p>
                                <p class="text-xs text-gray-600">Create a new webhook subscription</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium text-xs">3</span>
                            <div>
                                <p class="font-medium text-gray-900">Paste URL</p>
                                <p class="text-xs text-gray-600">Copy and paste the webhook URL from above (button with Copy)</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium text-xs">4</span>
                            <div>
                                <p class="font-medium text-gray-900">Select Events</p>
                                <p class="text-xs text-gray-600 mb-2">Enable these event types:</p>
                                <ul class="text-xs text-gray-600 space-y-1 ml-3">
                                    <li>✓ Counterparty Created</li>
                                    <li>✓ Counterparty Updated</li>
                                    <li>✓ Demand Created</li>
                                    <li>✓ Demand Updated</li>
                                    <li>✓ Supply Created</li>
                                    <li>✓ Supply Updated</li>
                                    <li>✓ Payment Created</li>
                                </ul>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-medium text-xs">5</span>
                            <div>
                                <p class="font-medium text-gray-900">Save & Test</p>
                                <p class="text-xs text-gray-600">Click Save, then Test to verify the webhook works</p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                        <p><strong>💡 Tip:</strong> You can also use the "Import Counterparties" button in the Integration page to manually sync existing customers.</p>
                    </div>
                </div>
            </div>

            <!-- Enable/Disable Events -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <livewire:webhook-event-toggle :bot="$bot" :key="'toggle-events-'.$bot->id" />
            </div>

            <!-- Event Statistics -->
            @if($eventStats->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold mb-4">📊 Webhook Activity</h2>

                    <div class="space-y-3">
                        @foreach($eventStats as $event)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $event->event_type }}</p>
                                    <p class="text-xs text-gray-500">
                                        Last received: {{ $event->last_received?->diffForHumans() ?? 'Never' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-brand-600">{{ $event->count }}</p>
                                    <p class="text-xs text-gray-500">events</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Events -->
            @if($recentEvents->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold mb-4">⏱️ Recent Events (Last 50)</h2>

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($recentEvents as $event)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <span class="text-xs font-medium text-gray-500 w-12 flex-shrink-0">
                                    {{ $event->created_at->format('H:i:s') }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-gray-900">{{ $event->event_type }}</span>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium {{
                                            $event->status === 'sent' ? 'bg-green-50 text-green-700' :
                                            ($event->status === 'failed' ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700')
                                        }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1 break-words">
                                        {{ json_encode($event->payload, JSON_UNESCAPED_SLASHES) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 text-center">
                    <p class="text-sm text-gray-600 mb-2">📭 No webhook events yet</p>
                    <p class="text-xs text-gray-500">
                        Events will appear here once МойСклад sends webhooks to this bot
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('✓ Copied to clipboard!');
        }).catch(() => {
            alert('Failed to copy. Please try manually.');
        });
    }
</script>
@endsection

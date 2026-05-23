@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Integrations</h1>
            <p class="text-sm text-gray-500 mt-1">Connect external services like МойСклад</p>
        </div>
        <livewire:create-integration-modal />
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($integrations->count() > 0)
        <div class="space-y-4">
            @foreach($integrations as $integration)
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900">
                                    {{ $integration->type === 'moisklad' ? '📊 МойСклад' : ucfirst($integration->type) }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $integration->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }} font-medium">
                                    {{ $integration->is_active ? 'Connected' : 'Disconnected' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">Added {{ $integration->created_at->diffForHumans() }}</p>
                        </div>
                        <form method="POST" action="{{ route('integrations.destroy', $integration->uuid) }}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-50 transition-colors">
                                Remove
                            </button>
                        </form>
                    </div>

                    @if($integration->type === 'moisklad')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-3">
                            <div>
                                <p class="text-xs font-medium text-blue-900 mb-1">Webhook URL:</p>
                                <div class="flex items-center gap-2">
                                    <code class="flex-1 text-xs bg-white p-2 rounded border border-blue-200 text-blue-900 break-all font-mono">
                                        {{ route('api.webhook.moisklad', [], true) }}
                                    </code>
                                    <button onclick="copyToClipboard('{{ route('api.webhook.moisklad', [], true) }}')" class="px-2 py-1 bg-white border border-blue-200 text-blue-600 rounded text-xs font-medium hover:bg-blue-100 transition-colors">
                                        Copy
                                    </button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-medium text-blue-900 mb-1">Setup Instructions:</p>
                                <ol class="text-xs text-blue-800 space-y-1 list-decimal list-inside">
                                    <li>Go to МойСклад Settings → Webhooks</li>
                                    <li>Add new webhook with the URL above</li>
                                    <li>Enable events: Supply, Demand, Payments</li>
                                    <li>Test the webhook</li>
                                </ol>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 mb-1">No integrations yet</h3>
            <p class="text-xs text-gray-500 mb-4">Connect external services to your bots</p>
            <livewire:create-integration-modal />
        </div>
    @endif
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied!');
        });
    }
</script>
@endsection

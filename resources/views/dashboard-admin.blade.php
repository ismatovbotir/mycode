@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div>
            <h1 class="text-xl font-semibold">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Bot Status</p>
            <p class="text-2xl font-bold mt-1">{{ auth()->user()->bot ? '✓ Active' : '○ Pending' }}</p>
            <p class="text-xs text-green-600 mt-1">{{ auth()->user()->bot ? 'Configured' : 'Not configured' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Total Clients</p>
            <p class="text-2xl font-bold mt-1">{{ auth()->user()->bot?->clients->count() ?? 0 }}</p>
            <p class="text-xs text-green-600 mt-1">Registered</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Integrations</p>
            <p class="text-2xl font-bold mt-1">{{ auth()->user()->bot?->integrations->count() ?? 0 }}</p>
            <p class="text-xs text-blue-600 mt-1">Connected</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium">Notifications</p>
            <p class="text-2xl font-bold mt-1">—</p>
            <p class="text-xs text-gray-400 mt-1">This month</p>
        </div>
    </div>

    <!-- Bot Section -->
    @if(auth()->user()->bot)
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold">Your Bot</h2>
                <a href="{{ route('bots.show', auth()->user()->bot) }}" class="text-xs text-brand-600 hover:underline">View details →</a>
            </div>
            <div>
                @php $bot = auth()->user()->bot; @endphp
                    <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg">🤖</div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold">{{ $bot->name }}</h3>
                                        <livewire:toggle-bot-active :bot="$bot" :key="'toggle-'.$bot->id" />
                                    </div>
                                    <div class="flex items-center gap-4 mt-1">
                                        <p class="text-sm text-gray-500">{{ $bot->clients->count() }} clients</p>
                                        <livewire:set-webhook-button :bot="$bot" :key="'webhook-'.$bot->id" />
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('bots.show', $bot) }}" class="text-xs text-brand-600 px-3 py-1.5 rounded-lg border border-brand-200 hover:bg-brand-50 transition-colors ml-4">
                                View Details
                            </a>
                        </div>
                    </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        Livewire.on('bot-created', () => {
            location.reload();
        });
    });
</script>
@endpush

@endsection

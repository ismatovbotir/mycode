@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Your Bots</h1>
            <p class="text-sm text-gray-500 mt-1">Manage all Telegram bots</p>
        </div>
        <livewire:create-bot-modal />
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($bots->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="divide-y divide-gray-50">
                @foreach($bots as $bot)
                    <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-lg">🤖</div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold">{{ $bot->name }}</h3>
                                        <span class="px-2 py-0.5 rounded-full text-xs {{ $bot->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }} font-medium">
                                            {{ $bot->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $bot->clients->count() }} clients</p>
                                </div>
                            </div>
                            <a href="{{ route('bots.show', $bot->uuid) }}" class="text-xs text-brand-600 px-3 py-1.5 rounded-lg border border-brand-200 hover:bg-brand-50 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            {{ $bots->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 mb-1">No bots yet</h3>
            <p class="text-xs text-gray-500 mb-4">Create your first Telegram bot to get started</p>
            <livewire:create-bot-modal />
        </div>
    @endif
</div>
@endsection

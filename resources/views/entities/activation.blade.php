@extends('layouts.app')

@section('title', 'Entity Activation')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Entity Activation</h1>
        <p class="text-gray-600 mt-2">Select which МойСклад entities you want to track and receive notifications for</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Available Entities Section -->
    <div class="mb-8">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Available Entities</h2>
            <p class="text-sm text-gray-600 mt-1">Click the button to activate an entity</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @php
                $notActivated = $allEntities->filter(fn($e) => !in_array($e->id, $activatedEntityIds));
            @endphp

            @if ($notActivated->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                    @foreach ($notActivated as $entity)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $entity->translations['en'] ?? $entity->type }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded text-xs font-medium">
                                            {{ $entity->type }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4 text-xs text-gray-600">
                                <p><strong>🇺🇿 Uzbek:</strong> {{ $entity->translations['uz'] ?? '—' }}</p>
                                <p><strong>🇷🇺 Russian:</strong> {{ $entity->translations['ru'] ?? '—' }}</p>
                            </div>

                            <form method="POST" action="{{ route('entities.activate', $entity) }}" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    + Activate
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50">
                    <p class="text-gray-500">All entities are activated! 🎉</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Activated Entities Section -->
    <div>
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Activated Entities</h2>
            <p class="text-sm text-gray-600 mt-1">Click the button to deactivate an entity</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            @php
                $activated = $allEntities->filter(fn($e) => in_array($e->id, $activatedEntityIds));
            @endphp

            @if ($activated->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                    @foreach ($activated as $entity)
                        <div class="border-2 border-green-200 rounded-lg p-4 hover:shadow-lg transition-shadow bg-green-50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $entity->translations['en'] ?? $entity->type }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <span class="inline-block bg-green-100 text-green-800 px-2.5 py-0.5 rounded text-xs font-medium">
                                            {{ $entity->type }}
                                        </span>
                                    </p>
                                </div>
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <div class="space-y-2 mb-4 text-xs text-gray-600">
                                <p><strong>🇺🇿 Uzbek:</strong> {{ $entity->translations['uz'] ?? '—' }}</p>
                                <p><strong>🇷🇺 Russian:</strong> {{ $entity->translations['ru'] ?? '—' }}</p>
                            </div>

                            <form method="POST" action="{{ route('entities.deactivate', $entity) }}" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    ✓ Deactivate
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50">
                    <p class="text-gray-500">No entities activated yet. Activate one to get started!</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="font-semibold text-blue-900 mb-1">What are entities?</h4>
                <p class="text-sm text-blue-700">Entities represent different types of transactions and events in МойСклад (Orders, Payments, Returns, etc.). Activate the entities you want to track, and you'll receive notifications when those events occur.</p>
            </div>
        </div>
    </div>
</div>
@endsection

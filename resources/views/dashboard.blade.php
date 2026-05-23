@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    Welcome, {{ auth()->user()->company->name }}!
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-blue-900">{{ auth()->user()->company->bots->count() }}</h2>
                        <p class="text-blue-600">Active Bots</p>
                    </div>

                    <div class="bg-green-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-green-900">
                            {{ auth()->user()->company->bots->sum(fn($b) => $b->clients->count()) }}
                        </h2>
                        <p class="text-green-600">Total Clients</p>
                    </div>

                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-purple-900">
                            {{ auth()->user()->company->integrations->count() }}
                        </h2>
                        <p class="text-purple-600">Integrations</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="flex space-x-4">
                        <a href="{{ route('bots.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Bot
                        </a>
                        <a href="{{ route('integrations.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Add Integration
                        </a>
                    </div>
                </div>

                @if (auth()->user()->company->bots->count() > 0)
                    <div class="mt-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Your Bots</h2>
                        <div class="space-y-4">
                            @foreach (auth()->user()->company->bots as $bot)
                                <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $bot->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $bot->clients->count() }} clients</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bots.show', $bot->uuid) }}" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 text-sm">
                                            View
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

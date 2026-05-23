@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">{{ $bot->name }} - Clients</h1>
        <p class="text-sm text-gray-500 mt-1">Manage clients for this bot</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($clients->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Name</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Phone</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Language</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Match</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Approval</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-700">Registered</th>
                            @if($bot->requires_admin_approval)
                                <th class="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($clients as $client)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-900">
                                    {{ $client->tgUser->first_name }} {{ $client->tgUser->last_name }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $client->tgUser->phone ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ match($client->tgUser->lang) {
                                        'uz' => '🇺🇿 Ўзбек',
                                        'ru' => '🇷🇺 Русский',
                                        'tj' => '🇹🇯 Тоҷикӣ',
                                        'kk' => '🏳️ Қарақалпақ',
                                        'kz' => '🇰🇿 Қазақ',
                                        default => '—'
                                    } }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $client->matched ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                        {{ $client->matched ? 'Matched' : 'Waiting' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $client->approved ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $client->approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $client->created_at->diffForHumans() }}
                                </td>
                                @if($bot->requires_admin_approval && !$client->approved)
                                    <td class="px-4 py-3">
                                        <div class="flex gap-1">
                                            <form method="POST" action="{{ route('bots.clients.approve', [$bot, $client]) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-green-600 px-2 py-1 rounded border border-green-200 hover:bg-green-50 transition-colors">
                                                    Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('bots.clients.reject', [$bot, $client]) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                <button type="submit" class="text-xs text-red-600 px-2 py-1 rounded border border-red-200 hover:bg-red-50 transition-colors">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 text-center">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 mb-1">No clients yet</h3>
            <p class="text-xs text-gray-500">Clients will appear here as they register through your bot</p>
        </div>
    @endif
</div>
@endsection

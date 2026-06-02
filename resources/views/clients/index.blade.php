@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Clients</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your bot clients and organize them into groups</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar: Group Management -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sticky top-6">
                <livewire:client-group-manager />
            </div>
        </div>

        <!-- Main: Clients Table -->
        <div class="lg:col-span-3">
            @if($clients->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Name</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Phone</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Language</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Bot</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Groups</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700">Registered</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($clients as $client)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-gray-900 font-medium">
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
                                            <a href="{{ route('bots.show', $client->bot) }}" class="text-brand-600 hover:underline text-xs font-medium">
                                                {{ $client->bot->name }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button
                                                onclick="toggleGroupPanel(this, {{ $client->id }})"
                                                class="text-xs text-brand-600 hover:text-brand-700 font-medium px-2 py-1 rounded hover:bg-brand-50 transition-colors"
                                            >
                                                Assign
                                            </button>
                                            <div id="group-panel-{{ $client->id }}" class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50 p-4">
                                                <div class="bg-white rounded-xl shadow-xl p-5 max-w-sm w-full">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <h3 class="font-semibold text-gray-900">
                                                            Assign to Groups
                                                        </h3>
                                                        <button
                                                            onclick="toggleGroupPanel(this, {{ $client->id }})"
                                                            class="text-gray-400 hover:text-gray-600"
                                                        >
                                                            ✕
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mb-4">
                                                        {{ $client->tgUser->first_name }} {{ $client->tgUser->last_name }} ({{ $client->bot->name }})
                                                    </p>
                                                    <livewire:client-group-assigner :client="$client" :key="'assign-'.$client->id" />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $client->created_at->diffForHumans() }}
                                        </td>
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
                    <p class="text-xs text-gray-500">Clients will appear here as they register through your bots</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleGroupPanel(button, clientId) {
        const panel = document.getElementById(`group-panel-${clientId}`);
        panel.classList.toggle('hidden');

        // Close when clicking outside
        if (!panel.classList.contains('hidden')) {
            panel.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }
    }
</script>
@endsection

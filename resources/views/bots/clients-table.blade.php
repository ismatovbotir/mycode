@if($clients->count() > 0)
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
                    @if(isset($bot) && $bot->requires_admin_approval)
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
                        @if(isset($bot) && $bot->requires_admin_approval && !$client->approved)
                            <td class="px-4 py-3">
                                <div class="flex gap-1">
                                    <form method="POST" action="{{ route('bots.clients.approve', [$bot->uuid, $client->uuid]) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-green-600 px-2 py-1 rounded border border-green-200 hover:bg-green-50 transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('bots.clients.reject', [$bot->uuid, $client->uuid]) }}" class="inline" onsubmit="return confirm('Are you sure?')">
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
@else
    <div class="text-center py-8">
        <p class="text-sm text-gray-500">No clients registered yet</p>
    </div>
@endif

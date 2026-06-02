<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">English</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Russian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Uzbek</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($entities as $entity)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="inline-block bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded text-xs font-medium">
                                {{ $entity->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entity->translations['en'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entity->translations['ru'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $entity->translations['uz'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-center">
                            @if ($entity->is_active)
                                <span class="inline-block bg-green-100 text-green-800 px-2.5 py-0.5 rounded text-xs font-medium">
                                    ✓ Active
                                </span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-2.5 py-0.5 rounded text-xs font-medium">
                                    ○ Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-right">
                            <button
                                wire:click="toggleActive({{ $entity->id }})"
                                wire:loading.attr="disabled"
                                class="@if ($entity->is_active) bg-red-600 hover:bg-red-700 @else bg-green-600 hover:bg-green-700 @endif text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50">
                                @if ($entity->is_active)
                                    Deactivate
                                @else
                                    Activate
                                @endif
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No entities found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

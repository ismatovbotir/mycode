<div>
    <!-- Import Button -->
    <button
        wire:click="openModal"
        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
    >
        📥 Import Counterparties
    </button>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg p-6 max-w-md w-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Import МойСклад Counterparties</h3>
                    <button
                        wire:click="closeModal"
                        @disabled($isImporting)
                        class="text-gray-400 hover:text-gray-600 disabled:opacity-50"
                    >
                        ✕
                    </button>
                </div>

                @if($status)
                    <div class="mb-4 p-4 rounded-lg {{ str_starts_with($status, '✓') ? 'bg-green-50 text-green-700' : (str_starts_with($status, '❌') ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700') }} text-sm">
                        {{ $status }}
                    </div>
                @endif

                @if($total > 0)
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-600">Progress</p>
                            <p class="text-xs text-gray-600">{{ $imported }}/{{ $total }}</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div
                                class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                style="width: {{ $total > 0 ? ($imported / $total * 100) : 0 }}%"
                            ></div>
                        </div>
                    </div>
                @endif

                <div class="flex gap-3">
                    <button
                        wire:click="import"
                        @disabled($isImporting)
                        class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        @if($isImporting)
                            <span class="inline-block animate-spin mr-2">⚙️</span> Importing...
                        @else
                            Import Now
                        @endif
                    </button>
                    <button
                        wire:click="closeModal"
                        @disabled($isImporting)
                        class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

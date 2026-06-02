<div>
    <!-- Button -->
    <button wire:click="openModal" class="flex items-center gap-2 bg-brand-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Integration
    </button>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" wire:click="closeModal">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto" @click.stop>
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                    <h2 class="font-semibold">Add Integration</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Form -->
                <form wire:submit="save" class="px-6 py-5 space-y-4">
                    <!-- Type -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Integration Type</label>
                        <select
                            wire:model="type"
                            required
                            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <option value="moisklad">📊 МойСклад</option>
                        </select>
                        @error('type')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dynamic Fields -->
                    @forelse($fields as $field)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">
                                {{ $field->label }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if($field->type === 'password')
                                <input
                                    type="password"
                                    wire:model="credentials.{{ $field->field_key }}"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($field->is_required) required @endif
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                            @elseif($field->type === 'url')
                                <input
                                    type="url"
                                    wire:model="credentials.{{ $field->field_key }}"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($field->is_required) required @endif
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                            @elseif($field->type === 'email')
                                <input
                                    type="email"
                                    wire:model="credentials.{{ $field->field_key }}"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($field->is_required) required @endif
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                            @else
                                <input
                                    type="text"
                                    wire:model="credentials.{{ $field->field_key }}"
                                    placeholder="{{ $field->placeholder }}"
                                    @if($field->is_required) required @endif
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                            @endif

                            @if($field->help_text)
                                <p class="text-xs text-gray-500 mt-1">{{ $field->help_text }}</p>
                            @endif

                            @error("credentials.{$field->field_key}")
                                <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No configuration fields found</p>
                    @endforelse

                    <!-- Test Connection -->
                    @if($fields->count() > 0)
                        <button
                            type="button"
                            wire:click="testConnection"
                            wire:loading.attr="disabled"
                            class="w-full text-sm bg-blue-100 text-blue-700 font-medium px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors disabled:opacity-50">
                            <span wire:loading.remove>🔗 Test Connection</span>
                            <span wire:loading>
                                <svg class="animate-spin inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Testing...
                            </span>
                        </button>

                        <!-- Test Result -->
                        @if($test_message)
                            <div class="p-3 rounded-lg {{ str_contains($test_message, '✓') ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }} text-xs font-medium">
                                {{ $test_message }}
                            </div>
                        @endif
                    @endif
                </form>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="text-sm text-gray-600 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button
                        type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="text-sm bg-brand-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-brand-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove>Add Integration</span>
                        <span wire:loading>
                            <svg class="animate-spin inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Adding...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

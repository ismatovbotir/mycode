@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Integration Field</h1>
        <p class="text-gray-600 text-sm mt-1">Update credential field configuration</p>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.integration-fields.update', $integrationField) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="integration_type" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Integration Type
                </label>
                <select
                    id="integration_type"
                    name="integration_type"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">— Select —</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" @selected(old('integration_type', $integrationField->integration_type) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('integration_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="field_key" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Field Key (machine name, lowercase with underscores)
                </label>
                <input
                    type="text"
                    id="field_key"
                    name="field_key"
                    required
                    pattern="^[a-z_]+$"
                    placeholder="api_token"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono"
                    value="{{ old('field_key', $integrationField->field_key) }}"
                />
                @error('field_key')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">e.g., api_token, base_url, org_id</p>
            </div>

            <div>
                <label for="label" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Display Label (shown in form)
                </label>
                <input
                    type="text"
                    id="label"
                    name="label"
                    required
                    placeholder="API Token"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    value="{{ old('label', $integrationField->label) }}"
                />
                @error('label')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Field Type
                </label>
                <select
                    id="type"
                    name="type"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">— Select —</option>
                    @foreach($fieldTypes as $key => $label)
                        <option value="{{ $key }}" @selected(old('type', $integrationField->type) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="placeholder" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Placeholder (optional)
                </label>
                <input
                    type="text"
                    id="placeholder"
                    name="placeholder"
                    placeholder="e.g., Enter your API token"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    value="{{ old('placeholder', $integrationField->placeholder) }}"
                />
                @error('placeholder')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="help_text" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Help Text (optional)
                </label>
                <textarea
                    id="help_text"
                    name="help_text"
                    rows="3"
                    placeholder="Provide helpful information about this field..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >{{ old('help_text', $integrationField->help_text) }}</textarea>
                @error('help_text')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Sort Order (0 = first)
                </label>
                <input
                    type="number"
                    id="sort_order"
                    name="sort_order"
                    min="0"
                    max="999"
                    placeholder="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    value="{{ old('sort_order', $integrationField->sort_order) }}"
                />
                @error('sort_order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_required"
                        value="1"
                        @checked(old('is_required', $integrationField->is_required))
                        class="w-4 h-4 text-blue-600 rounded"
                    />
                    <span class="text-sm font-medium text-gray-700">This field is required</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $integrationField->is_active))
                        class="w-4 h-4 text-blue-600 rounded"
                    />
                    <span class="text-sm font-medium text-gray-700">This field is active</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors"
                >
                    Update Field
                </button>
                <a
                    href="{{ route('admin.integration-fields.index') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

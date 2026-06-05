@extends('layouts.admin-super')

@section('title', 'Edit Entity')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Entity</h1>
        <p class="text-sm text-gray-600 mt-1"><a href="{{ route('admin.entities.index') }}" class="text-brand-600 hover:text-brand-700">Back to Entities</a></p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.entities.update', $entity) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <input type="text" name="type" id="type" value="{{ old('type', $entity->type) }}" placeholder="e.g., demand, cashin"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('type') border-red-500 @enderror"
                    required>
                <p class="mt-1 text-xs text-gray-500">Machine-readable type, e.g. demand, cashin, paymentin</p>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="uz" class="block text-sm font-medium text-gray-700 mb-1">🇺🇿 Uzbek</label>
                    <input type="text" name="translations[uz]" id="uz" value="{{ old('translations.uz', $entity->translations['uz'] ?? '') }}" placeholder="e.g., Buyurtma"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('translations.uz') border-red-500 @enderror"
                        required>
                    @error('translations.uz')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="en" class="block text-sm font-medium text-gray-700 mb-1">🇬🇧 English</label>
                    <input type="text" name="translations[en]" id="en" value="{{ old('translations.en', $entity->translations['en'] ?? '') }}" placeholder="e.g., Demand"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('translations.en') border-red-500 @enderror"
                        required>
                    @error('translations.en')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ru" class="block text-sm font-medium text-gray-700 mb-1">🇷🇺 Russian</label>
                    <input type="text" name="translations[ru]" id="ru" value="{{ old('translations.ru', $entity->translations['ru'] ?? '') }}" placeholder="e.g., Заказ"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent @error('translations.ru') border-red-500 @enderror"
                        required>
                    @error('translations.ru')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Document Entity Configuration -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_document" id="is_document" value="1"
                            {{ old('is_document', $entity->is_document) ? 'checked' : '' }}
                            class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div class="flex-1">
                        <label for="is_document" class="block text-sm font-medium text-gray-900 cursor-pointer">
                            Document Type Entity
                        </label>
                        <p class="text-xs text-gray-600 mt-1">
                            Enable this for document entities (demand, supply, invoice, etc.) that should send formatted Telegram messages to clients
                        </p>
                    </div>
                </div>
            </div>

            <!-- Document Format Configuration -->
            @if(old('is_document', $entity->is_document))
            <div id="document-format-section" class="space-y-3">
                <div>
                    <label for="document_format" class="block text-sm font-medium text-gray-700 mb-2">Document Format (JSON)</label>
                    <p class="text-xs text-gray-600 mb-3">Define which document fields to display in Telegram message. Example:</p>
                    <pre class="bg-gray-50 text-xs text-gray-700 p-3 rounded mb-3 overflow-x-auto"><code>{
  "header": ["name", "date", "counterparty", "sum"],
  "items": ["name", "quantity", "price", "sum"],
  "footer": ["total"]
}</code></pre>
                    <textarea name="document_format" id="document_format" rows="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-transparent font-mono text-sm @error('document_format') border-red-500 @enderror"
                        placeholder='{"header": [], "items": [], "footer": []}'>{{ old('document_format', json_encode($entity->document_format ?? [])) }}</textarea>
                    @error('document_format')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <script>
                document.getElementById('is_document').addEventListener('change', function() {
                    const section = document.getElementById('document-format-section');
                    if (this.checked) {
                        section.style.display = '';
                    } else {
                        section.style.display = 'none';
                    }
                });
            </script>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Update Entity
                </button>
                <a href="{{ route('admin.entities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

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

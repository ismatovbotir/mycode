@extends('layouts.admin-super')

@section('title', 'Entity: ' . $entity->type)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $entity->type }}</h1>
                <p class="text-sm text-gray-600 mt-1"><a href="{{ route('admin.entities.index') }}" class="text-brand-600 hover:text-brand-700">← Back to Entities</a></p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.entities.edit', $entity) }}" class="bg-brand-600 hover:bg-brand-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    ✏️ Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Entity Status -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-600 font-medium mb-1">Status</p>
            <p class="text-lg font-semibold">
                @if($entity->is_active)
                    <span class="text-green-600">✅ Active</span>
                @else
                    <span class="text-gray-500">⭕ Inactive</span>
                @endif
            </p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-600 font-medium mb-1">Type</p>
            <p class="text-lg font-semibold text-gray-900">{{ $entity->type }}</p>
        </div>
    </div>

    <!-- Translations -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Translations</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach(['uz' => '🇺🇿 Uzbek', 'en' => '🇬🇧 English', 'ru' => '🇷🇺 Russian'] as $code => $label)
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-1">{{ $label }}</p>
                    <p class="text-sm text-gray-900">{{ $entity->translations[$code] ?? '—' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Document Configuration -->
    @if($entity->is_document)
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <h2 class="text-lg font-semibold text-gray-900">📄 Document Configuration</h2>
            <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded">Document Type</span>
        </div>

        @if($entity->document_format)
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 font-medium mb-2">Message Format</p>
                    <div class="bg-white rounded border border-gray-200 p-3 font-mono text-xs overflow-x-auto">
                        <pre>{{ json_encode($entity->document_format, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-600">No format configuration set</p>
        @endif
    </div>
    @else
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6">
        <p class="text-sm text-gray-600">This is not a document-type entity</p>
    </div>
    @endif

    <!-- Messages Configuration -->
    @if($entity->messages)
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Message Templates</h2>
        <div class="space-y-4">
            @foreach(['uz' => '🇺🇿 Uzbek', 'en' => '🇬🇧 English', 'ru' => '🇷🇺 Russian'] as $code => $label)
                @if(isset($entity->messages[$code]))
                <div>
                    <p class="text-sm font-medium text-gray-900 mb-2">{{ $label }}</p>
                    <div class="bg-gray-50 rounded border border-gray-200 p-3 text-sm text-gray-700 whitespace-pre-wrap">
                        {{ $entity->messages[$code] }}
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

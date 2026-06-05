@extends('layouts.app')

@section('title', 'Edit Bot: ' . $bot->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Bot</h1>
                <p class="text-gray-600 mt-1">{{ $bot->name }}</p>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <livewire:edit-bot-form :bot="$bot" />
    </div>
</div>
@endsection

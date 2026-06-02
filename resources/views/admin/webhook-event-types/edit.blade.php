@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Webhook Event Type</h1>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.webhook-event-types.update', $eventType) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="event_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Type
                    </label>
                    <input
                        type="text"
                        id="event_type"
                        name="event_type"
                        value="{{ old('event_type', $eventType->event_type) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                    @error('event_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Display Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $eventType->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('description', $eventType->description) }}</textarea>
                </div>

                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                        Icon (emoji)
                    </label>
                    <input
                        type="text"
                        id="icon"
                        name="icon"
                        value="{{ old('icon', $eventType->icon) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        maxlength="10"
                        required
                    />
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            @checked(old('is_active', $eventType->is_active))
                            class="w-4 h-4 text-blue-600 rounded"
                        />
                        <span class="text-sm font-medium text-gray-700">Active (users can enable this event)</span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Update Event Type
                    </button>
                    <a
                        href="{{ route('admin.webhook-event-types.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Webhook Event Types</h1>
                <p class="text-gray-500 mt-1">Define webhook events that users can enable for their bots</p>
            </div>
            <a href="{{ route('admin.webhook-event-types.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Add Event Type
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Event Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($eventTypes as $eventType)
                        <tr>
                            <td class="px-6 py-4 text-2xl">{{ $eventType->icon }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $eventType->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $eventType->description }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-gray-600">
                                {{ $eventType->event_type }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $eventType->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $eventType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('admin.webhook-event-types.edit', $eventType) }}" class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.webhook-event-types.destroy', $eventType) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this event type?')" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No webhook event types defined yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($eventTypes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $eventTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

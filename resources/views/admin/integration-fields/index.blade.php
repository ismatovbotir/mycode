@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Integration Fields</h1>
            <p class="text-gray-600 text-sm mt-1">Manage integration credential fields for МойСклад, Bitrix24, and 1C</p>
        </div>
        <a href="{{ route('admin.integration-fields.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
            + Add Field
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Integration Type</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Field Key</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Label</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Required</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($fields as $field)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <span class="inline-block px-2.5 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                    @switch($field->integration_type)
                                        @case('moysklad')
                                            МойСклад
                                            @break
                                        @case('bitrix')
                                            Bitrix24
                                            @break
                                        @case('1c')
                                            1C
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-3 font-mono text-gray-700">{{ $field->field_key }}</td>
                            <td class="px-6 py-3 text-gray-900">{{ $field->label }}</td>
                            <td class="px-6 py-3">
                                <span class="text-gray-600">{{ ucfirst($field->type) }}</span>
                            </td>
                            <td class="px-6 py-3">
                                @if($field->is_required)
                                    <span class="text-green-600 font-medium">✓ Yes</span>
                                @else
                                    <span class="text-gray-500">Optional</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if($field->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.integration-fields.edit', $field) }}" class="text-blue-600 hover:text-blue-700 font-medium text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.integration-fields.destroy', $field) }}" class="inline" onsubmit="return confirm('Delete this field?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No integration fields defined yet. <a href="{{ route('admin.integration-fields.create') }}" class="text-blue-600 hover:underline">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($fields->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $fields->firstItem() }} to {{ $fields->lastItem() }} of {{ $fields->total() }} fields
                </div>
                <div class="flex gap-2">
                    {{ $fields->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

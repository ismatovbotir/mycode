@extends('layouts.admin-super')

@section('title', 'Entities Management')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Entities Management</h1>
            <p class="text-sm text-gray-600 mt-1">Activate/deactivate МойСклад entity types</p>
        </div>
    </div>

    <livewire:admin.entity-manager />
</div>
@endsection

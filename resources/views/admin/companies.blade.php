@extends('layouts.admin-super')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Companies</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all companies and their subscriptions</p>
    </div>

    <!-- Companies Table -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Company</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">INN</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Owner</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Bots</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Clients</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\Company::with('users', 'bots')->paginate(20) as $company)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $company->name }}
                            </td>
                            <td class="px-4 py-3 font-mono text-gray-600">
                                {{ $company->inn }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">{{ $company->users->first()?->name ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ $company->users->first()?->email ?? '—' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 font-medium">
                                    {{ $company->bots->count() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $company->bots->sum(fn($b) => $b->clients->count()) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 font-medium">
                                    Active
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $company->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 text-sm">
                                No companies found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

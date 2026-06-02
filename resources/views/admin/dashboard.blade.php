@extends('layouts.admin-super')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">System overview and statistics</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-gray-400">
            <span class="w-2 h-2 bg-green-500 rounded-full inline-block animate-pulse"></span>
            Updated just now
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium">Companies</p>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
            </div>
            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
            <p class="text-xs text-green-600 mt-1">↑ Active</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium">Total Users</p>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
            <p class="text-xs text-blue-600 mt-1">All registered</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium">Total Bots</p>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <p class="text-3xl font-bold">{{ \App\Models\Bot::count() }}</p>
            <p class="text-xs text-purple-600 mt-1">Active bots</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium">Total Clients</p>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 19H9a6 6 0 016-6v0a6 6 0 016 6v0z"/></svg>
            </div>
            <p class="text-3xl font-bold">{{ \App\Models\BotClient::count() }}</p>
            <p class="text-xs text-orange-600 mt-1">Registered</p>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold">Recent Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Email</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Bots</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Role</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\User::with('bot')->latest()->take(10)->get() as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 font-medium">
                                    {{ $user->bot ? 1 : 0 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                <span class="px-2 py-1 rounded bg-gray-100">{{ $user->role }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-sm">
                                No users yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

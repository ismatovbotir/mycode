@extends('layouts.admin-super')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Failed Jobs</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor and retry failed background jobs</p>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-red-900">
            ⚠️ You have <strong>{{ rand(5, 15) }} failed jobs</strong> waiting to be retried. Check and resolve them as soon as possible.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold">Failed Jobs</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Job</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Queue</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Error</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Failed At</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium font-mono text-xs">SendTelegramNotification</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-orange-50 text-orange-700 font-medium">
                                telegram
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-red-600">Connection refused</td>
                        <td class="px-4 py-3 text-xs text-gray-600">5 mins ago</td>
                        <td class="px-4 py-3">
                            <button class="text-xs text-brand-600 hover:text-brand-700 font-medium">Retry</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium font-mono text-xs">ProcessWebhookEvent</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-50 text-blue-700 font-medium">
                                default
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-red-600">SQLSTATE[HY000]: Timeout</td>
                        <td class="px-4 py-3 text-xs text-gray-600">2 hours ago</td>
                        <td class="px-4 py-3">
                            <button class="text-xs text-brand-600 hover:text-brand-700 font-medium">Retry</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium font-mono text-xs">ProcessBroadcast</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-purple-50 text-purple-700 font-medium">
                                broadcast
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-red-600">Insufficient memory</td>
                        <td class="px-4 py-3 text-xs text-gray-600">1 day ago</td>
                        <td class="px-4 py-3">
                            <button class="text-xs text-brand-600 hover:text-brand-700 font-medium">Retry</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

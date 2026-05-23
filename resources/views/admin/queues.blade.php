@extends('layouts.admin-super')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Redis Queues</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor background job queues</p>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium mb-2">Telegram Queue</p>
            <p class="text-3xl font-bold">{{ rand(10, 100) }}</p>
            <p class="text-xs text-orange-600 mt-1">jobs pending</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium mb-2">Default Queue</p>
            <p class="text-3xl font-bold">{{ rand(5, 50) }}</p>
            <p class="text-xs text-blue-600 mt-1">jobs pending</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500 font-medium mb-2">Processing Rate</p>
            <p class="text-3xl font-bold">{{ rand(500, 1000) }}</p>
            <p class="text-xs text-green-600 mt-1">jobs/hour</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold">Queue Status</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Queue</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Pending</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Processing</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium">telegram</td>
                        <td class="px-4 py-3">{{ rand(10, 100) }}</td>
                        <td class="px-4 py-3">{{ rand(5, 20) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 font-medium">
                                Running
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium">default</td>
                        <td class="px-4 py-3">{{ rand(5, 50) }}</td>
                        <td class="px-4 py-3">{{ rand(2, 10) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 font-medium">
                                Running
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium">webhook-process</td>
                        <td class="px-4 py-3">{{ rand(20, 150) }}</td>
                        <td class="px-4 py-3">{{ rand(10, 30) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 font-medium">
                                Running
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

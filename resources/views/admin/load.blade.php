@extends('layouts.admin-super')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">System Load</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor server performance and resource usage</p>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs text-gray-500 font-medium mb-2">CPU Usage</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold">{{ rand(20, 60) }}%</p>
                <p class="text-xs text-gray-400">of available</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="bg-brand-600 h-2 rounded-full" style="width: {{ rand(20, 60) }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs text-gray-500 font-medium mb-2">Memory Usage</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold">{{ rand(40, 80) }}%</p>
                <p class="text-xs text-gray-400">of 16GB</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ rand(40, 80) }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs text-gray-500 font-medium mb-2">Disk Usage</p>
            <div class="flex items-baseline gap-2">
                <p class="text-3xl font-bold">{{ rand(30, 60) }}%</p>
                <p class="text-xs text-gray-400">of available</p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ rand(30, 60) }}%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm font-semibold mb-4">Real-time Metrics</p>
        <div class="space-y-4">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Request Rate</span>
                <span class="font-semibold">{{ rand(100, 500) }} req/min</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Average Response Time</span>
                <span class="font-semibold">{{ rand(45, 200) }}ms</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Active Connections</span>
                <span class="font-semibold">{{ rand(50, 200) }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Database Queries</span>
                <span class="font-semibold">{{ rand(1000, 5000) }}/min</span>
            </div>
        </div>
    </div>
</div>
@endsection

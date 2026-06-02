@extends('layouts.admin-super')

@section('title', 'Management Dashboard')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Management</h1>
        <p class="text-gray-600 mt-2">Configure system settings and manage МойСклад integration</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Webhook Event Types -->
        <a href="{{ route('admin.webhook-event-types.index') }}" class="group">
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Webhook Events</h3>
                <p class="text-sm text-gray-600 mb-4">Manage МойСклад webhook event types and descriptions</p>
                <div class="flex items-center gap-2 text-xs text-yellow-600 font-medium">
                    <span class="w-2 h-2 bg-yellow-600 rounded-full"></span>
                    {{ \App\Models\WebhookEventType::count() }} events
                </div>
            </div>
        </a>

        <!-- Integration Fields -->
        <a href="{{ route('admin.integration-fields.index') }}" class="group">
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Integration Fields</h3>
                <p class="text-sm text-gray-600 mb-4">Configure required credential fields for МойСклад integration</p>
                <div class="flex items-center gap-2 text-xs text-blue-600 font-medium">
                    <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                    {{ \App\Models\IntegrationField::count() }} fields
                </div>
            </div>
        </a>

        <!-- Entities -->
        <a href="{{ route('admin.entities.index') }}" class="group">
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.5a2 2 0 00-1 .24l-1.697 1.13a2 2 0 01-2.12 0l-1.697-1.13A2 2 0 007.5 9H5a2 2 0 00-2 2v4a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Entities</h3>
                <p class="text-sm text-gray-600 mb-4">Manage МойСклад entity types (demand, payments, returns)</p>
                <div class="flex items-center gap-2 text-xs text-purple-600 font-medium">
                    <span class="w-2 h-2 bg-purple-600 rounded-full"></span>
                    {{ \App\Models\Entity::count() }} entities
                </div>
            </div>
        </a>

        <!-- МойСклад Webhooks -->
        <a href="{{ route('admin.moysklad-webhooks.index') }}" class="group">
            <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Webhooks</h3>
                <p class="text-sm text-gray-600 mb-4">Configure МойСклад webhooks for entity events</p>
                <div class="flex items-center gap-2 text-xs text-green-600 font-medium">
                    <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                    {{ \App\Models\MoySkladEntityWebhook::where('is_active', true)->count() }} active
                </div>
            </div>
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
            <p class="text-xs text-blue-600 font-medium uppercase tracking-wide mb-2">Active Webhook Events</p>
            <p class="text-2xl font-bold text-blue-900">{{ \App\Models\WebhookEventType::where('is_active', true)->count() }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
            <p class="text-xs text-green-600 font-medium uppercase tracking-wide mb-2">Required Fields</p>
            <p class="text-2xl font-bold text-green-900">{{ \App\Models\IntegrationField::where('is_required', true)->count() }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4">
            <p class="text-xs text-purple-600 font-medium uppercase tracking-wide mb-2">Total Entities</p>
            <p class="text-2xl font-bold text-purple-900">{{ \App\Models\Entity::count() }}</p>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4">
            <p class="text-xs text-orange-600 font-medium uppercase tracking-wide mb-2">Integration Types</p>
            <p class="text-2xl font-bold text-orange-900">{{ \App\Models\IntegrationField::distinct('integration_type')->count('integration_type') }}</p>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="font-semibold text-blue-900 mb-1">МойСклад Integration</h4>
                <p class="text-sm text-blue-700">Configure webhook events, integration fields, and entity types to manage how МойСклад events are processed and sent to users.</p>
            </div>
        </div>
    </div>
</div>
@endsection

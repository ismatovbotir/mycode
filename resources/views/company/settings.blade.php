@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Company Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your company profile</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('company.updateSettings') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Name -->
            <div>
                <label for="name" class="block text-xs font-medium text-gray-600 mb-1.5">Company Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $company->name) }}"
                    required
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                @error('name')
                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- INN -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">INN</label>
                <input
                    type="text"
                    value="{{ $company->inn }}"
                    disabled
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-gray-50 text-gray-500"/>
                <p class="text-xs text-gray-400 mt-1">Cannot be changed</p>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Email Address</label>
                <input
                    type="email"
                    value="{{ $company->email }}"
                    disabled
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-gray-50 text-gray-500"/>
                <p class="text-xs text-gray-400 mt-1">Cannot be changed</p>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1.5">Mobile Number</label>
                <div class="flex gap-2">
                    <select
                        name="country_code"
                        class="w-24 text-sm border border-gray-200 rounded-lg px-2 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                        @php
                            $phoneData = $company->phone ? preg_match('/^(\+\d{1,3})(.+)$/', $company->phone, $matches) ? $matches : [null, '+998', $company->phone] : [null, '+998', ''];
                            $savedCountryCode = old('country_code', $phoneData[1] ?? '+998');
                            $savedPhone = old('phone', $phoneData[2] ?? '');
                        @endphp
                        <option value="+998" {{ $savedCountryCode === '+998' ? 'selected' : '' }}>🇺🇿 +998</option>
                        <option value="+7" {{ $savedCountryCode === '+7' ? 'selected' : '' }}>🇰🇿 +7</option>
                        <option value="+992" {{ $savedCountryCode === '+992' ? 'selected' : '' }}>🇹🇯 +992</option>
                        <option value="+7">🇷🇺 +7</option>
                    </select>
                    <input
                        name="phone"
                        type="tel"
                        value="{{ $savedPhone }}"
                        class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        placeholder="901234567"
                        pattern="[0-9]{9,}"/>
                </div>
                @error('phone')
                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                @enderror
                @error('country_code')
                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Website -->
            <div>
                <label for="website" class="block text-xs font-medium text-gray-600 mb-1.5">Website</label>
                <input
                    id="website"
                    name="website"
                    type="url"
                    value="{{ old('website', $company->website) }}"
                    placeholder="https://example.com"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500"/>
                @error('website')
                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Timezone -->
            <div>
                <label for="timezone" class="block text-xs font-medium text-gray-600 mb-1.5">Timezone</label>
                <select
                    id="timezone"
                    name="timezone"
                    required
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="Asia/Tashkent" {{ $company->timezone === 'Asia/Tashkent' ? 'selected' : '' }}>Asia/Tashkent</option>
                    <option value="UTC">UTC</option>
                    <option value="Europe/Moscow">Europe/Moscow</option>
                    <option value="Asia/Dushanbe">Asia/Dushanbe</option>
                </select>
                @error('timezone')
                    <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 pt-4 border-t border-gray-100">
                <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

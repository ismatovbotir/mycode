@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">MyCode</h1>
            <p class="text-gray-600">Create your account</p>
        </div>

        <!-- Language Tabs -->
        <div class="flex gap-2 mb-6" x-data="{ lang: 'uz' }">
            <button @click="lang = 'uz'" :class="lang === 'uz' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200'" class="flex-1 py-2 px-4 rounded-lg font-medium transition-colors">
                🇺🇿 O'zbek
            </button>
            <button @click="lang = 'en'" :class="lang === 'en' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200'" class="flex-1 py-2 px-4 rounded-lg font-medium transition-colors">
                🇬🇧 English
            </button>
            <button @click="lang = 'ru'" :class="lang === 'ru' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200'" class="flex-1 py-2 px-4 rounded-lg font-medium transition-colors">
                🇷🇺 Русский
            </button>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-5">
            @csrf
            <input type="hidden" name="lang" x-model="lang">

            <!-- Brand Name -->
            <div>
                <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">Kompaniya nomi</span>
                    <span x-show="lang === 'en'">User Name</span>
                    <span x-show="lang === 'ru'">Название компании</span>
                </label>
                <input
                    id="brand_name"
                    name="brand_name"
                    type="text"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    :placeholder="lang === 'uz' ? 'MyUser' : (lang === 'en' ? 'MyUser' : 'МояКомпания')"
                    value="{{ old('brand_name') }}"
                />
                @error('brand_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Full Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">To'liq isim</span>
                    <span x-show="lang === 'en'">Full Name</span>
                    <span x-show="lang === 'ru'">Полное имя</span>
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    :placeholder="lang === 'uz' ? 'Ism Familiya' : (lang === 'en' ? 'John Doe' : 'Иван Иванов')"
                    value="{{ old('name') }}"
                />
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">Email</span>
                    <span x-show="lang === 'en'">Email Address</span>
                    <span x-show="lang === 'ru'">Email</span>
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">Telefon</span>
                    <span x-show="lang === 'en'">Phone Number</span>
                    <span x-show="lang === 'ru'">Номер телефона</span>
                </label>
                <div class="flex gap-2">
                    <select
                        name="country_code"
                        class="px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                    >
                        <option value="+998" @selected(old('country_code') === '+998')>+998 (UZ)</option>
                        <option value="+1" @selected(old('country_code') === '+1')>+1 (US/CA)</option>
                        <option value="+44" @selected(old('country_code') === '+44')>+44 (UK)</option>
                        <option value="+7" @selected(old('country_code') === '+7')>+7 (RU)</option>
                        <option value="+33" @selected(old('country_code') === '+33')>+33 (FR)</option>
                        <option value="+49" @selected(old('country_code') === '+49')>+49 (DE)</option>
                    </select>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        required
                        class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="901234567"
                        pattern="\d{9,}"
                        value="{{ old('phone') }}"
                    />
                </div>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('country_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">Parol</span>
                    <span x-show="lang === 'en'">Password</span>
                    <span x-show="lang === 'ru'">Пароль</span>
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    :placeholder="lang === 'uz' ? 'Kamida 8 ta belgisi' : (lang === 'en' ? 'At least 8 characters' : 'Минимум 8 символов')"
                />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-show="lang === 'uz'">Parolni tasdiqlang</span>
                    <span x-show="lang === 'en'">Confirm Password</span>
                    <span x-show="lang === 'ru'">Подтвердите пароль</span>
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                    :placeholder="lang === 'uz' ? 'Parolni qayta kiriting' : (lang === 'en' ? 'Confirm your password' : 'Повторите пароль')"
                />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <span x-show="lang === 'uz'">Ro'yxatdan o'tish</span>
                <span x-show="lang === 'en'">Create Account</span>
                <span x-show="lang === 'ru'">Создать аккаунт</span>
            </button>

            <!-- Login Link -->
            <p class="text-center text-sm text-gray-600">
                <span x-show="lang === 'uz'">Allaqachon akkauntingiz bormi?</span>
                <span x-show="lang === 'en'">Already have an account?</span>
                <span x-show="lang === 'ru'">Уже есть аккаунт?</span>
                <a href="{{ route('login') }}" class="ml-1 font-medium text-blue-600 hover:text-blue-700">
                    <span x-show="lang === 'uz'">Kirish</span>
                    <span x-show="lang === 'en'">Sign in</span>
                    <span x-show="lang === 'ru'">Войти</span>
                </a>
            </p>
        </form>
    </div>
</div>
@endsection

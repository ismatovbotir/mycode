<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - MyCode</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#f0f9ff', 100:'#e0f2fe', 500:'#0ea5e9', 600:'#0284c7', 700:'#0369a1' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-brand-600 rounded-xl mb-4">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.2-.04-.28-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.42-.88.03-.25.38-.51 1.07-.78 4.19-1.82 6.98-3.02 8.38-3.61 3.99-1.66 4.82-1.95 5.36-1.96.12 0 .38.03.55.18.14.13.18.3.2.43-.02.07-.02.13-.03.2z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">MyCode</h1>
        <p class="text-sm text-gray-500 mt-1">Create your company account</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Company Info -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-3">Company Information</h3>

                <div>
                    <label for="company_name" class="block text-xs font-medium text-gray-600 mb-1.5">Company Name</label>
                    <input
                        id="company_name"
                        name="company_name"
                        type="text"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="Your Company"
                        value="{{ old('company_name') }}">
                </div>

                <div class="mt-3">
                    <label for="inn" class="block text-xs font-medium text-gray-600 mb-1.5">INN (9 digits)</label>
                    <input
                        id="inn"
                        name="inn"
                        type="text"
                        required
                        maxlength="9"
                        pattern="\d{9}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="123456789"
                        value="{{ old('inn') }}">
                </div>
            </div>

            <!-- Owner Info -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-3">Your Information</h3>

                <div>
                    <label for="name" class="block text-xs font-medium text-gray-600 mb-1.5">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="John Doe"
                        value="{{ old('name') }}">
                </div>

                <div class="mt-3">
                    <label for="email" class="block text-xs font-medium text-gray-600 mb-1.5">Email Address</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="you@example.com"
                        value="{{ old('email') }}">
                </div>

                <div class="mt-3">
                    <label for="phone" class="block text-xs font-medium text-gray-600 mb-1.5">Mobile Number</label>
                    <div class="flex gap-2">
                        <select
                            name="country_code"
                            class="w-24 text-sm border border-gray-200 rounded-lg px-2 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all bg-white">
                            <option value="+998" selected>🇺🇿 +998</option>
                            <option value="+7">🇰🇿 +7</option>
                            <option value="+992">🇹🇯 +992</option>
                            <option value="+7">🇷🇺 +7</option>
                        </select>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            required
                            class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                            placeholder="901234567"
                            value="{{ old('phone') }}"
                            pattern="[0-9]{9,}">
                    </div>
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Language -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-3">Preferences</h3>

                <label for="lang" class="block text-xs font-medium text-gray-600 mb-1.5">Preferred Language</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-brand-300 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                        <input type="radio" name="lang" value="uz" class="text-brand-600" {{ old('lang') == 'uz' ? 'checked' : (!old('lang') ? 'checked' : '') }}>
                        <span class="text-sm font-medium">🇺🇿 Ўзбек</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-brand-300 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                        <input type="radio" name="lang" value="ru" class="text-brand-600" {{ old('lang') == 'ru' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">🇷🇺 Русский</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-brand-300 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                        <input type="radio" name="lang" value="tj" class="text-brand-600" {{ old('lang') == 'tj' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">🇹🇯 Тоҷикӣ</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-brand-300 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                        <input type="radio" name="lang" value="kk" class="text-brand-600" {{ old('lang') == 'kk' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">🏳️ Қарақалпақ</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-brand-300 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50">
                        <input type="radio" name="lang" value="kz" class="text-brand-600" {{ old('lang') == 'kz' ? 'checked' : '' }}>
                        <span class="text-sm font-medium">🇰🇿 Қазақша</span>
                    </label>
                </div>
                @error('lang')
                    <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <h3 class="text-xs font-semibold text-gray-600 uppercase mb-3">Security</h3>

                <div>
                    <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="••••••••">
                    <p class="text-xs text-gray-400 mt-1">At least 8 characters</p>
                </div>

                <div class="mt-3">
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-600 mb-1.5">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 mt-6">
                Create Account
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100">
            <p class="text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>

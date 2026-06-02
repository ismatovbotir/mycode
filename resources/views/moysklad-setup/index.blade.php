<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>МойСклад Setup - MyCode</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen p-4 bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="text-5xl mb-3">📊</div>
                <h1 class="text-4xl font-bold text-gray-900">МойСклад Setup</h1>
                <p class="text-gray-600 mt-2">Connect your МойСклад account to get started</p>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Tutorial (Left) -->
                <div>
                    <livewire:moysklad-token-tutorial />
                </div>

                <!-- Form (Right) -->
                <div class="bg-white rounded-2xl shadow-xl p-8 h-fit sticky top-4">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">🔑 {{ __('Enter Your Token') }}</h2>
                        <p class="text-gray-600 mt-2">{{ __('After completing the steps on the left, paste your token here.') }}</p>
                    </div>

                    <!-- Form -->
                    <livewire:moysklad-setup />

                    <!-- Footer -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <form method="POST" action="{{ route('logout') }}" class="text-center">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                Use different account
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Responsive: Single Column -->
            <div class="lg:hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-center">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">💻 {{ __('For better experience, please use desktop view for this tutorial.') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

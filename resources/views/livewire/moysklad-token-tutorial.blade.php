<div class="w-full bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header with Language Selector -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-white">🎓 {{ __('How to Get МойСклад Token') }}</h2>

            <!-- Language Selector -->
            <div class="flex gap-2">
                @foreach(['ru' => '🇷🇺 РУ', 'uz' => '🇺🇿 UZ', 'kk' => '🏳️ KK', 'kz' => '🇰🇿 KZ', 'tj' => '🇹🇯 TJ'] as $lang => $label)
                    <button
                        wire:click="setLanguage('{{ $lang }}')"
                        class="px-3 py-1 rounded text-sm font-semibold transition-all {{ $language === $lang
                            ? 'bg-white text-blue-600 shadow'
                            : 'bg-blue-500 text-white hover:bg-blue-400' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-gray-100 px-6 py-2">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">
                {{ __('Step') }} {{ $currentStep }} / {{ $totalSteps }}
            </span>
            <span class="text-xs text-gray-500">{{ round(($currentStep / $totalSteps) * 100) }}%</span>
        </div>
        <div class="w-full bg-gray-300 rounded-full h-2">
            <div
                class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300"
                style="width: {{ ($currentStep / $totalSteps) * 100 }}%"
            ></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8">
        <!-- Title and Description -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $stepData['title'] }}</h3>
            <p class="text-gray-600 leading-relaxed text-lg">{{ $stepData['description'] }}</p>
        </div>

        <!-- Screenshot -->
        <div class="mb-6">
            <div class="bg-gray-50 rounded-lg overflow-hidden shadow border border-gray-200">
                <img
                    src="{{ $stepData['image'] }}"
                    alt="Step {{ $currentStep }}"
                    class="w-full h-auto"
                >
            </div>
        </div>

        <!-- Step Indicators -->
        <div class="flex gap-2 mb-6 justify-center">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <button
                    wire:click="goToStep({{ $i }})"
                    class="w-10 h-10 rounded-full font-semibold transition-all {{ $currentStep === $i
                        ? 'bg-blue-600 text-white shadow-lg scale-110'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    {{ $i }}
                </button>
            @endfor
        </div>

        <!-- Navigation Buttons -->
        <div class="flex gap-4 justify-between">
            <button
                @if ($currentStep > 1)
                    wire:click="previousStep"
                @else
                    disabled
                @endif
                class="flex-1 px-4 py-3 rounded-lg font-semibold transition-all {{ $currentStep > 1
                    ? 'bg-gray-200 text-gray-900 hover:bg-gray-300 cursor-pointer'
                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
            >
                ← {{ __('Previous') }}
            </button>

            <button
                @if ($currentStep < $totalSteps)
                    wire:click="nextStep"
                @else
                    disabled
                @endif
                class="flex-1 px-4 py-3 rounded-lg font-semibold transition-all {{ $currentStep < $totalSteps
                    ? 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer'
                    : 'bg-green-600 text-white cursor-not-allowed' }}"
            >
                @if ($currentStep < $totalSteps)
                    {{ __('Next') }} →
                @else
                    ✓ {{ __('Done') }}
                @endif
            </button>
        </div>
    </div>

    <!-- Footer Tips -->
    @if ($currentStep === $totalSteps)
        <div class="bg-yellow-50 border-t-2 border-yellow-300 px-6 py-4">
            <div class="flex gap-3">
                <span class="text-2xl">💡</span>
                <div>
                    <p class="font-semibold text-yellow-900 mb-1">{{ __('Important Tips:') }}</p>
                    <ul class="text-sm text-yellow-800 space-y-1 list-disc list-inside">
                        <li>{{ __('Save your token to a secure place - you can view it only once') }}</li>
                        <li>{{ __('Never share your token with anyone') }}</li>
                        <li>{{ __('Paste the copied token in the field below and click "Test Connection"') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

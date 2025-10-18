<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Strategy Configuration') }} - {{ strtoupper($strategyConfig->timeframe) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.strategy-configs.update', $strategyConfig) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="default_ema_length" :value="__('Default EMA Length')" />
                            <x-text-input type="number" id="default_ema_length" name="default_ema_length" value="{{ old('default_ema_length', $strategyConfig->default_ema_length) }}" class="mt-1 block w-full" min="5" max="200" />
                            <x-input-error :messages="$errors->get('default_ema_length')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-2">
                            <x-checkbox id="is_enabled" name="is_enabled" value="1" {{ old('is_enabled', $strategyConfig->is_enabled) ? 'checked' : '' }} />
                            <label for="is_enabled">{{ __('Enabled') }}</label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.strategy-configs.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Cancel') }}</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

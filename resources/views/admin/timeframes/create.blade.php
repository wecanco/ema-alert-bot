<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Timeframe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.timeframes.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="code" :value="__('Code')" />
                            <x-text-input id="code" name="code" value="{{ old('code') }}" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="label" :value="__('Label')" />
                            <x-text-input id="label" name="label" value="{{ old('label') }}" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('label')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="minutes" :value="__('Minutes')" />
                            <x-text-input type="number" id="minutes" name="minutes" value="{{ old('minutes') }}" class="mt-1 block w-full" min="1" required />
                            <x-input-error :messages="$errors->get('minutes')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-2">
                            <x-checkbox id="is_enabled" name="is_enabled" value="1" checked />
                            <label for="is_enabled">{{ __('Enabled') }}</label>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.timeframes.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Cancel') }}</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

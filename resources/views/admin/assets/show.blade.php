<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ strtoupper($asset->symbol) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Exchange') }}</h3>
                        <p class="mt-1">{{ strtoupper($asset->exchange) }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Base Currency') }}</h3>
                        <p class="mt-1">{{ $asset->base_currency ?? __('Not set') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
                        <p class="mt-1">{{ $asset->is_active ? __('Active') : __('Disabled') }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.assets.edit', $asset) }}" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Edit') }}</a>
                        <a href="{{ route('admin.assets.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Tracked Assets') }}</h3>
                        <p class="text-3xl font-bold">{{ $stats['assets'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Active Watches') }}</h3>
                        <p class="text-3xl font-bold">{{ $stats['watches'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-2">{{ __('Alerts Today') }}</h3>
                        <p class="text-3xl font-bold">{{ $stats['alerts_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

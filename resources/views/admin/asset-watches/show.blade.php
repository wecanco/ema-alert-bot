<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Watch Detail') }} - {{ strtoupper($assetWatch->asset->symbol) }} / {{ strtoupper($assetWatch->timeframe) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Asset') }}</h3>
                        <p class="mt-1">{{ strtoupper($assetWatch->asset->symbol) }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Timeframe') }}</h3>
                        <p class="mt-1">{{ strtoupper($assetWatch->timeframe) }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('EMA Length') }}</h3>
                        <p class="mt-1">{{ $assetWatch->ema_length }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Owner') }}</h3>
                        <p class="mt-1">{{ $assetWatch->owner?->name ?? __('All admins') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
                        <p class="mt-1">{{ $assetWatch->is_active ? __('Active') : __('Disabled') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Last Alert At') }}</h3>
                        <p class="mt-1">{{ optional($assetWatch->last_alert_at)->toDateTimeString() ?? __('Never') }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.asset-watches.edit', $assetWatch) }}" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Edit') }}</a>
                        <a href="{{ route('admin.asset-watches.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Back') }}</a>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Recent Alerts') }}</h3>
                    <div class="space-y-3">
                        @forelse($assetWatch->alertEvents()->latest()->take(10)->get() as $event)
                            <div class="border border-gray-200 rounded p-3">
                                <div class="flex justify-between">
                                    <span>{{ $event->triggered_at->toDateTimeString() }}</span>
                                    <span class="font-semibold">{{ $event->price }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ __('Provider:') }} {{ $event->provider ?? 'N/A' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('No alerts yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

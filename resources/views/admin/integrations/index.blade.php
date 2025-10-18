<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Integrations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @foreach($integrations as $integration)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $integration->name }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($integration->type) }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.integrations.toggle', $integration) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $integration->is_active ? 0 : 1 }}">
                                <x-primary-button>
                                    {{ $integration->is_active ? __('Disable') : __('Enable') }}
                                </x-primary-button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('admin.integrations.update', $integration) }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label :value="__('Configuration (JSON)')" />
                                <textarea name="config" class="mt-1 block w-full border-gray-300 rounded" rows="4">{{ old('config', json_encode($integration->config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>
                                <x-input-error :messages="$errors->get('config')" class="mt-2" />
                            </div>

                            <input type="hidden" name="is_active" value="{{ $integration->is_active ? 1 : 0 }}">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.integrations.export', $integration) }}" class="px-4 py-2 bg-gray-200 rounded" target="_blank">{{ __('Export') }}</a>
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

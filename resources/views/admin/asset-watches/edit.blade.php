<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Asset Watch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.asset-watches.update', $assetWatch) }}" class="space-y-6">
                        @method('PUT')
                        @include('admin.asset-watches.partials.form')

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.asset-watches.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Cancel') }}</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.asset-watches.destroy', $assetWatch) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">{{ __('Deleting removes watch history.') }}</p>
                            <x-danger-button>{{ __('Delete Watch') }}</x-danger-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

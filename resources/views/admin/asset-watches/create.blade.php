<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Asset Watch') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.asset-watches.store') }}" class="space-y-6">
                        @include('admin.asset-watches.partials.form', ['assetWatch' => null])

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.asset-watches.index') }}" class="px-4 py-2 bg-gray-200 rounded">{{ __('Cancel') }}</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

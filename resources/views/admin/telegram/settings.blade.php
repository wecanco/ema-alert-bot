<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Telegram Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.telegram.test') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="chat_id" :value="__('Chat ID')" />
                            <x-text-input id="chat_id" name="chat_id" value="{{ old('chat_id', auth()->user()->telegram_chat_id) }}" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('chat_id')" class="mt-2" />
                        </div>
                        <x-primary-button>{{ __('Send Test Message') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

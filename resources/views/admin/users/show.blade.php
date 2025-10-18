<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Email') }}</h3>
                        <p class="mt-1">{{ $user->email }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Role') }}</h3>
                        <p class="mt-1">{{ ucfirst($user->role) }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Telegram Chat ID') }}</h3>
                        <p class="mt-1">{{ $user->telegram_chat_id ?? __('Not configured') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Telegram Notifications') }}</h3>
                        <p class="mt-1">{{ $user->telegram_notifications_enabled ? __('Enabled') : __('Disabled') }}</p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Edit') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

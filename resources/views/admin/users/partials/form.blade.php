@csrf

<div>
    <x-input-label for="name" :value="__('Name')" />
    <x-text-input id="name" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="email" :value="__('Email')" />
    <x-text-input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div>
    <x-input-label for="password" :value="__('Password')" />
    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" {{ isset($user) ? '' : 'required' }} />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
    @isset($user)
        <p class="text-xs text-gray-500 mt-1">{{ __('Leave blank to keep current password.') }}</p>
    @endisset
</div>

<div>
    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" {{ isset($user) ? '' : 'required' }} />
</div>

<div>
    <x-input-label for="role" :value="__('Role')" />
    <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded">
        <option value="user" @selected(old('role', $user->role ?? 'user') === 'user')>{{ __('User') }}</option>
        <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>{{ __('Admin') }}</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>

<div>
    <x-input-label for="telegram_chat_id" :value="__('Telegram Chat ID')" />
    <x-text-input id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $user->telegram_chat_id ?? '') }}" class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('telegram_chat_id')" class="mt-2" />
</div>

<div class="flex items-center gap-2">
    <x-checkbox id="telegram_notifications_enabled" name="telegram_notifications_enabled" value="1" {{ old('telegram_notifications_enabled', $user->telegram_notifications_enabled ?? false) ? 'checked' : '' }} />
    <label for="telegram_notifications_enabled">{{ __('Enable Telegram notifications') }}</label>
</div>

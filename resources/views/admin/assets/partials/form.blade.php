@csrf

<div>
    <x-input-label for="symbol" :value="__('Symbol')" />
    <x-text-input id="symbol" name="symbol" value="{{ old('symbol', $asset->symbol ?? '') }}" class="mt-1 block w-full" required />
    <x-input-error :messages="$errors->get('symbol')" class="mt-2" />
</div>

<div>
    <x-input-label for="exchange" :value="__('Exchange')" />
    <select id="exchange" name="exchange" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        @foreach($exchanges as $exchange)
            <option value="{{ $exchange['key'] }}" {{ old('exchange', $asset->exchange ?? 'binance') == $exchange['key'] ? 'selected' : '' }}>
                {{ $exchange['name'] }} ({{ $exchange['base_url'] }})
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('exchange')" class="mt-2" />
</div>

<div>
    <x-input-label for="base_currency" :value="__('Base Currency')" />
    <x-text-input id="base_currency" name="base_currency" value="{{ old('base_currency', $asset->base_currency ?? '') }}" class="mt-1 block w-full" />
    <x-input-error :messages="$errors->get('base_currency')" class="mt-2" />
</div>

<div class="flex items-center gap-2">
    <x-checkbox id="is_active" name="is_active" value="1" {{ old('is_active', $asset->is_active ?? true) ? 'checked' : '' }} />
    <label for="is_active">{{ __('Active') }}</label>
</div>

@csrf

<div>
    <x-input-label for="asset_id" :value="__('Asset')" />
    <select id="asset_id" name="asset_id" class="mt-1 block w-full border-gray-300 rounded">
        @foreach($assets as $assetOption)
            <option value="{{ $assetOption->id }}" @selected(old('asset_id', optional($assetWatch)->asset_id) == $assetOption->id)>{{ strtoupper($assetOption->symbol) }} ({{ strtoupper($assetOption->exchange) }})</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
</div>

<div>
    <x-input-label for="user_id" :value="__('Owner')" />
    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 rounded">
        <option value="" @selected(! old('user_id', optional($assetWatch)->user_id))>{{ __('All admins') }}</option>
        @foreach($users as $userOption)
            <option value="{{ $userOption->id }}" @selected(old('user_id', optional($assetWatch)->user_id) == $userOption->id)>{{ $userOption->name }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
</div>

<div>
    <x-input-label for="timeframe" :value="__('Timeframe')" />
    <select id="timeframe" name="timeframe" class="mt-1 block w-full border-gray-300 rounded">
        @foreach($timeframes as $timeframeOption)
            <option value="{{ $timeframeOption->code }}" @selected(old('timeframe', optional($assetWatch)->timeframe) == $timeframeOption->code)>{{ $timeframeOption->label }}</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('timeframe')" class="mt-2" />
</div>

<div>
    <x-input-label for="ema_length" :value="__('EMA Length')" />
    <x-text-input type="number" id="ema_length" name="ema_length" value="{{ old('ema_length', optional($assetWatch)->ema_length ?? 50) }}" class="mt-1 block w-full" min="5" max="200" />
    <x-input-error :messages="$errors->get('ema_length')" class="mt-2" />
</div>

<div class="flex items-center gap-2">
    <x-checkbox id="is_active" name="is_active" value="1" {{ old('is_active', optional($assetWatch)->is_active ?? true) ? 'checked' : '' }} />
    <label for="is_active">{{ __('Active') }}</label>
</div>

@props(['id' => $id ?? null, 'name' => $name ?? null, 'value' => $value ?? 1, 'checked' => $checked ?? false])

<input type="checkbox" {{ $id ? "id=$id" : '' }} {{ $name ? "name=$name" : '' }} value="{{ $value }}" {{ $checked ? 'checked' : '' }} {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500']) }}>

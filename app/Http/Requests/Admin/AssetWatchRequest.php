<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssetWatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('access-admin-panel') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'asset_id' => ['required', 'exists:assets,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'timeframe' => ['required', 'string', 'max:16'],
            'ema_length' => ['required', 'integer', 'min:5', 'max:200'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}

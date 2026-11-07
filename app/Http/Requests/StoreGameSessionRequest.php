<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'session_number' => ['sometimes', 'integer', 'min:1'],
            'session_date' => ['required', 'date'],
            'location_id' => ['nullable', 'string'],
            'npcs' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'session_summary' => ['nullable', 'string']
        ];
    }
}

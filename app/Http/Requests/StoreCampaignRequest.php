<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
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
            'campaign_name' => ['required', 'string', 'max255'],
            'ruleset' => ['sometiemes', 'in:2014'],
            'campaign_start_date' => ['required','date'],
            'campaign_end_date' => ['nullable', 'date', 'after_or_equal:campaign_start_date']
        ];
    }
}

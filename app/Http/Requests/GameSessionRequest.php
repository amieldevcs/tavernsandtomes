<?php

namespace App\Http\Requests;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Rules shared by "create" and "update". The two subclasses only differ in
 * HOW they find the campaign and which session (if any) to ignore.
 */
abstract class GameSessionRequest extends FormRequest
{
    // The campaign this request is about.
    abstract protected function campaign(): Campaign;

    // On update, the session being edited, so it doesn't clash with itself.
    abstract protected function ignoredSessionId(): ?string;

    // Runs BEFORE validation, so outsiders get a 403 and never learn
    // anything from validation messages.
    public function authorize(): bool
    {
        return $this->user()?->can('manageSessions', $this->campaign()) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        // PUT replaces everything, so the date is required. PATCH sends
        // only what changed, so it's "sometimes".
        $presence = $this->isMethod('PATCH') ? 'sometimes' : 'required';

        return [
            'session_number' => [
                'sometimes',
                'integer',
                'min:1',
                'max:32767', // the column is a smallInteger
                // Unique, but only within THIS campaign, and ignoring the
                // session currently being edited.
                Rule::unique('sessions', 'session_number')
                    ->where('campaign_id', $this->campaign()->getKey())
                    ->ignore($this->ignoredSessionId(), 'session_id'),
            ],
            'session_date' => [$presence, 'date'],
            'location_id' => ['nullable', 'string', 'max:255'],
            'npcs' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'dm_notes' => ['nullable', 'string'],
            'session_summary' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateForwarderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->destinations)) {
            $destinations = array_filter(array_map('trim', explode(',', $this->destinations)));
            $this->merge(['destinations' => array_values($destinations)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'alias' => [
                'required',
                'string',
                'min:1',
                'max:64',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'destinations' => [
                'required',
                'array',
                'min:1',
            ],
            'destinations.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $val = trim($value);
                    if ($val === ':blackhole:' || $val === ':fail:') {
                        return;
                    }
                    if (! filter_var($val, FILTER_VALIDATE_EMAIL)) {
                        $fail("The destination '{$val}' must be a valid email address or special target (:blackhole:, :fail:).");
                    }
                },
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'alias.required' => 'The forwarder alias is required.',
            'alias.regex' => 'The forwarder alias may only contain letters, numbers, dots, dashes, and underscores.',
            'destinations.required' => 'At least one destination email is required.',
            'destinations.min' => 'At least one destination email is required.',
        ];
    }
}

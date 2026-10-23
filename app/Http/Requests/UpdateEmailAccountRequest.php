<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmailAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:72',
            ],
            'quota' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'limit' => [
                'nullable',
                'integer',
                'min:1',
                'max:9600',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 72 characters.',
            'quota.min' => 'Storage quota must be at least 0 MB.',
            'limit.min' => 'Daily sending limit must be at least 1.',
            'limit.max' => 'Daily sending limit cannot exceed 9600.',
        ];
    }
}

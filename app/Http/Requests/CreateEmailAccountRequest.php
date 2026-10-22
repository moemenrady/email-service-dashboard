<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmailAccountRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:1',
                'max:64',
                'regex:/^[a-zA-Z0-9._-]+$/',
            ],
            'password' => [
                'required',
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
            'username.required' => 'The mailbox username is required.',
            'username.regex' => 'The username may only contain letters, numbers, dots, dashes, and underscores.',
            'password.required' => 'A password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 72 characters.',
            'quota.min' => 'Storage quota must be at least 0 MB (0 = unlimited).',
            'limit.min' => 'Daily sending limit must be at least 1.',
            'limit.max' => 'Daily sending limit cannot exceed 9600.',
        ];
    }
}

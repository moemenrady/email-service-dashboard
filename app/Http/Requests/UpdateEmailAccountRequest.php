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
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 خانات.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 72 خانة.',
            'quota.min' => 'المساحة التخزينية يجب ألا تقل عن 0 ميجابايت.',
            'limit.min' => 'الحد اليومي للإرسال يجب أن يكون 1 على الأقل.',
            'limit.max' => 'الحد اليومي للإرسال لا يمكن أن يتجاوز 9600 رسالة.',
        ];
    }
}

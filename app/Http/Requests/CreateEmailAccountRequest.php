<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array<mixed>|string>
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
            'username.required' => 'اسم المستخدم للبريد الإلكتروني مطلوب.',
            'username.regex' => 'اسم المستخدم يجب أن يحتوي فقط على أحرف إنجليزية، أرقام، نقاط، أو شرطات.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 خانات.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 72 خانة.',
            'quota.min' => 'المساحة التخزينية يجب ألا تقل عن 0 ميجابايت (0 = غير محدود).',
            'limit.min' => 'الحد اليومي للإرسال يجب أن يكون 1 على الأقل.',
            'limit.max' => 'الحد اليومي للإرسال لا يمكن أن يتجاوز 9600 رسالة.',
        ];
    }
}

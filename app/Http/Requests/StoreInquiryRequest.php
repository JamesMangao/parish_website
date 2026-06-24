<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'inquiryType' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'fullName.required' => 'Please provide your full name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'inquiryType.required' => 'Please select the type of inquiry.',
            'message.required' => 'Please describe your inquiry.',
            'message.max' => 'Your message must not exceed 2000 characters.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:1000|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Please enter a message.',
            'message.max' => 'Your message must not exceed 1000 characters.',
            'message.min' => 'Your message must be at least 1 character.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIntentionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullName' => 'required|string|max:255',
            'intentionType' => 'required|string|max:100',
            'preferredDate' => 'nullable|date|after_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'paymentMethod' => 'nullable|string|in:cash,gcash,bank_transfer',
        ];
    }

    public function messages(): array
    {
        return [
            'fullName.required' => 'Please provide the name for this intention.',
            'intentionType.required' => 'Please select the type of intention.',
            'preferredDate.after_or_equal' => 'The preferred date must be today or later.',
            'description.max' => 'The description must not exceed 1000 characters.',
        ];
    }
}

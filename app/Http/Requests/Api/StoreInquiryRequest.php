<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->name) ? trim($this->name) : $this->name,
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'phone' => is_string($this->phone) ? trim($this->phone) : $this->phone,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:5', 'max:3000'],
            'product_id' => ['nullable', 'integer', Rule::exists('products', 'id')->where('status', true)],
            'website' => ['nullable', 'string', 'max:255'], // honeypot – keep this input hidden on the form
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid phone number.',
            'message.required' => 'Please enter your message.',
            'product_id.exists' => 'The selected product is not available.',
        ];
    }
}

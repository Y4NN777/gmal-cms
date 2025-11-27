<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'position' => ['nullable', 'string', 'max:100'],
            'organization' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string', 'min:50'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar_id' => ['nullable', 'exists:media,id'],
            'is_featured' => ['boolean'],
            'status' => ['nullable', 'in:pending,approved,rejected'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.max' => 'Name cannot exceed 100 characters.',
            'email.email' => 'Please provide a valid email address.',
            'content.required' => 'Testimonial content is required.',
            'content.min' => 'Testimonial must be at least 50 characters long.',
            'rating.required' => 'Rating is required.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'avatar_id.exists' => 'Selected avatar image does not exist.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'event_date' => ['required', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published,archived,cancelled'],
            'is_featured' => ['boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
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
            'title.required' => 'Event title is required.',
            'description.required' => 'Event description is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'event_date.required' => 'Event date is required.',
            'start_date.required' => 'Start date is required.',
            'end_date.after' => 'End date must be after start date.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'meta_title.max' => 'Meta title cannot exceed 60 characters.',
            'meta_description.max' => 'Meta description cannot exceed 160 characters.',
        ];
    }
}

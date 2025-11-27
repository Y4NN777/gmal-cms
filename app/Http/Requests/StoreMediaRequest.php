<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isEditor();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:jpeg,jpg,png,gif,webp,svg,pdf,doc,docx',
                'max:10240', // 10MB
            ],
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'folder_id' => 'nullable|exists:media_folders,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'File must be an image (jpeg, jpg, png, gif, webp, svg) or document (pdf, doc, docx).',
            'file.max' => 'File size must not exceed 10MB.',
        ];
    }
}

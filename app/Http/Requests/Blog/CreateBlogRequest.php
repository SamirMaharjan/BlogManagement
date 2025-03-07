<?php

namespace App\Http\Requests\Blog;

use App\Traits\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateBlogRequest extends FormRequest
{
    use ResponseMessage;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|array', // Multiple images allowed
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Each image should be valid
        ];
    }

    /**
     * Custom messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The blog title is required.',
            'title.string' => 'The blog title must be a valid text.',
            'title.max' => 'The blog title cannot exceed 255 characters.',
            
            'content.required' => 'The blog content is required.',
            'content.string' => 'The blog content must be a valid text.',

            'images.array' => 'The images must be an array of files.',
            'images.*.image' => 'Each file must be a valid image.',
            'images.*.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed.',
            'images.*.max' => 'Each image must not exceed 2MB in size.'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $this->handleFailedValidation($validator);
    }
}

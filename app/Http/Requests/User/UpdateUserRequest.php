<?php

namespace App\Http\Requests\User;

use App\Traits\ResponseMessage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    use ResponseMessage;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to 'true' for simplicity. Adjust as needed.
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'min:3',
                'max:125',
            ],
            'email' => [
                'sometimes',
                'email',
               'unique:users,email,' . $this->id,
            ],
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'confirmed', // Ensures "password_confirmation" matches "password"
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.min' => 'The name must be at least 3 characters.',
            'name.max' => 'The name may not exceed 125 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The passwords do not match.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $this->handleFailedValidation($validator);
    }
}

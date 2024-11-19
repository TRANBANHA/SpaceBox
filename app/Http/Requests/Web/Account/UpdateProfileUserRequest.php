<?php

namespace App\Http\Requests\Web\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'nullable|string|max:255',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }
}

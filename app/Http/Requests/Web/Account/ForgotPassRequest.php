<?php

namespace App\Http\Requests\Web\Account;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassRequest extends FormRequest
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
            'email' => 'required|exists:users,email'
        ];
    }
    public function messages(){
        return [
            'email.exists' => 'Email không tồn tại'
        ];
    }
}

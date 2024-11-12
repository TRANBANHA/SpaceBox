<?php

namespace App\Http\Requests\Web\Account;

use Auth;
use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'passwordOld' => 'required|string',
            'password' => 'required|string|confirmed',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->passwordOld, Auth::user()->password)) {
                $validator->errors()->add('passwordOld', 'Mật khẩu hiện tại không đúng.');
            }
        });
    }
    public function messages()
    {
        return [
            'passwordOld.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu nhập lại không trùng khớp.'
        ];
    }
}

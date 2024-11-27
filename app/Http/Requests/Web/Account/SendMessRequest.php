<?php

namespace App\Http\Requests\Web\Account;

use Illuminate\Foundation\Http\FormRequest;

class SendMessRequest extends FormRequest
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
            'room_id' => ['required', 'integer'],
            'content' => ['required', 'string'],
        ];
    }
}

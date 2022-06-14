<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'min:1', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required'],
            'photo' => ['nullable'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string'],
        ];
    }
}

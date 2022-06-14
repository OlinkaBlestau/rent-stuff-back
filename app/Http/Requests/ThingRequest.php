<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThingRequest extends FormRequest
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
            'price' => ['required'],
            'description' => ['required', 'string'],
            'photo' => ['required'],
            'shop_id' => ['exists:shops,id'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}

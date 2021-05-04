<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TesteRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'site' => 'required|url|max:100'
        ];
    }

    public function messages()
    {
        return [
            'sites.required' => 'Preencha pelo menos um site!',
            'name.required' => 'Name is required!',
            'password.required' => 'Password is required!'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'site' => 'required|url|max:100',
            'tool_name' => 'required|max:100',

        ];
    }

    public function messages()
    {
        return [
            'site.required' => 'Campo Obrigatório!',
            'site.url' => 'Formato inválido',
            'tool_name.required' => 'Campo Obrigatório',
        ];
    }
}

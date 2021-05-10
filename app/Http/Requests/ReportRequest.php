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
            'sites.*' => 'required|array|max:100',
            'sites.*.url' => 'required|url|max:100',
            'sites.*.tool_name' => 'required|max:100',

        ];
    }

    public function messages()
    {
        return [
            'sites.*.required' => 'Preencha pelo menos um site!',
            'sites.*.url' => [
                'required' => 'Campo Obrigatório',
                'url' => 'Formato inválido'
            ],
            'sites.*.tool_name' => [
                'required' => 'Campo Obrigatório'
            ],
        ];
    }
}

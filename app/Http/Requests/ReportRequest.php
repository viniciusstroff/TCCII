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
            'sites.*' => 'required|url|max:100',
            'tool_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'sites.required' => 'Preencha pelo menos um site!',
            'tool_name.required' => 'Tool is required!',
        ];
    }
}

<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class ReporteFormRequest extends Request
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
            'fechaInicio'=>'required',
            'fechaFin'=>'required',
            'formato'=>'required',
        ];
    }
}

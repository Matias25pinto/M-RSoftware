<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class CajaFormRequest extends Request
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
            'usuario'=>'max:255',
            '100mil'=>'required|numeric|min:0',
            '50mil'=>'required|numeric|min:0',
            '20mil'=>'required|numeric|min:0',
            '10mil'=>'required|numeric|min:0',
            '5mil'=>'required|numeric|min:0',
            'moneda_1000'=>'required|numeric|min:0',
            'moneda_500'=>'required|numeric|min:0',
            'moneda_100'=>'required|numeric|min:0',
            'moneda_50'=>'required|numeric|min:0',
        ];
    }
}

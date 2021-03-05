<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class CFacturaFormRequest extends Request
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
            'cliente'=>'required',
            'nro_factura'=>'required',
            'exentas'=>'required|numeric',
            'impuesto5'=>'required|numeric',
            'impuesto10'=>'required|numeric',
            'fechaInicio'=>'required',
        ];
    }
}

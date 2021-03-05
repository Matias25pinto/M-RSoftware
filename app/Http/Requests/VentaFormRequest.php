<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class VentaFormRequest extends Request
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
            'idcliente'=>'required',
            'idvendedor'=>'required',
            'tipo_comprobante'=>'required|max:20',
            'serie_comprobante'=>'max:7',
            'num_comprobante'=>'max:10',
            'idarticulo'=>'required',
            'cantidad'=>'required',
            'precio_venta'=>'required|min:1',
            'total_venta'=>'required|min:1',
            'importe'=>'required'
        ];
    }
}

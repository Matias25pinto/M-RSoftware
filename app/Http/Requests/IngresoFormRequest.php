<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class IngresoFormRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'idproveedor'=>'required',
            'tipo_comprobante'=>'required|max:20',
            'num_comprobante'=>'required',
            'idarticulo'=>'required',
            'cantidad'=>'required',
            'precio_compra'=>'required',
            'timbrado'=>'required|numeric',
            'descuento'=>'required|Min:0',
            'bonificacion'=>'required',
            'cuotas'=>'required|Integer|Min:0',
            'fechaInicio'=>'required',
           
        ];
    }
}

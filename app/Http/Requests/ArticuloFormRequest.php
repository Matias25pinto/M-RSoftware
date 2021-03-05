<?php

namespace sisMR\Http\Requests;
use sisMR\Http\Requests\Request;

class ArticuloFormRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        
        return [
            'idcategoria'=>'required',
            'codigo'=>'required|max:50|unique:articulo,codigo,'. $this->id .',idarticulo',
            'nombre'=>'required|max:100',
            'stock'=>'numeric',
            'stock_minimo'=>'numeric',
            'precio_compra'=>'required|numeric|min:0',
            'precio_venta'=>'required|numeric|min:0',
            'precio_venta2'=>'required|numeric|min:0',
            'precio_venta3'=>'required|numeric|min:0',
            'impuesto'=>'required|numeric',
            'imagen'=>'mimes:jpeg,bmp,png,jpg'
        ];
    }
}

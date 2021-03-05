<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class AjusteFormRequest extends Request
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
            'cantidad'=>'required|numeric|integer',
            'estado'=>'required|max:20',
            'detalle'=>'max:200'
        ];
    }
}

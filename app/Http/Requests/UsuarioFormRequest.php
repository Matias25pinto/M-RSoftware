<?php

namespace sisMR\Http\Requests;

use sisMR\Http\Requests\Request;

class UsuarioFormRequest extends Request
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
            'name' => 'required|max:255',
            'nro_talonario' => 'required|max:7',
            'timbrado' => 'required|max:11',
            'email' => 'required|email|max:255|unique:users,email,'. $this->email .',email',
            'permisos' => 'required',
            'password' => 'required|min:6|confirmed',
        ];
    }
}

<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;

use DB;
use sisMR\Persona;

class ActualizarRucController extends Controller
{
    public function __construct()
    {
         $this->middleware(['auth',
            'verificarPermiso:administrador,ventas,contador'
            ]);
    }
    public function index(Request $request)
    {    
            return view('actualizar.ruc.index');
        
    }

    public function actualizarclientes($nro_ruc)
    {
            $personas=DB::table('persona')
            ->where ('tipo_persona','=','Cliente')
            ->orderBy('idpersona','desc')
            ->get();

            $tipo = 'Clientes';

            return view('actualizar.ruc.actualizados',['nro_ruc'=>$nro_ruc,'personas'=>$personas,'tipo'=>$tipo]);

             
    } 
    public function actualizarproveedores($nro_ruc)
    {
        $personas=DB::table('persona')
        ->where ('tipo_persona','=','Proveedor')
        ->orderBy('idpersona','desc')
        ->get();

        $tipo = 'Proveedores';

        return view('actualizar.ruc.actualizados',['nro_ruc'=>$nro_ruc,'personas'=>$personas,'tipo'=>$tipo]);
    }

    public function base_set($nro_ruc)
    {
        $personas=DB::table('base_set')
        ->get();

        $tipo = 'TODOS';

        return view('actualizar.ruc.base_set',['nro_ruc'=>$nro_ruc,'personas'=>$personas,'tipo'=>$tipo]);
    }

}

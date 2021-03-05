<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;

use sisMR\User;
use Illuminate\Support\Facades\Redirect;
use sisMR\Http\Requests\UsuarioFormRequest;
use DB;

class UsuarioController extends Controller
{
	public function __construct()
    {
 $this->middleware(['auth',
            'verificarPermiso:administrador'
            ]);
    }

    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            $usuarios=DB::table('users as u')->where('name','LIKE','%'.$query.'%')
            ->where('u.permisos','!=',"administrador")
            ->orderBy('u.id','desc')
       
            ->paginate(7);
            return view('seguridad.usuario.index',["usuarios"=>$usuarios,"searchText"=>$query]);
        }
    }

    public function create()
    {
        return view("seguridad.usuario.create");
    }
    public function store (UsuarioFormRequest $request)
    {
        $usuario=new User;
        $usuario->name=$request->get('name');
        $usuario->nro_talonario=$request->get('nro_talonario');
        $usuario->timbrado=$request->get('timbrado');
        $usuario->email=$request->get('email');
        $usuario->permisos=$request->get('permisos');
        $usuario->password=bcrypt($request->get('password'));
        $usuario->save();
        return Redirect::to('seguridad/usuario');
    }
    public function edit($id)
    {
        return view("seguridad.usuario.edit",["usuario"=>User::findOrFail($id)]);
    }    
    public function update(UsuarioFormRequest $request,$id)
    {
        $usuario=User::findOrFail($id);
        $usuario->name=$request->get('name');
        $usuario->nro_talonario=$request->get('nro_talonario');
        $usuario->timbrado=$request->get('timbrado');
        $usuario->email=$request->get('email');
        $usuario->permisos=$request->get('permisos');
        $usuario->password=bcrypt($request->get('password'));
        $usuario->update();
        return Redirect::to('seguridad/usuario');
    }
    public function destroy($id)
    {
        $usuario = DB::table('users')->where('id', '=', $id)->delete();
        return Redirect::to('seguridad/usuario');
    }
}

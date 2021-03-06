<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use sisMR\Http\Requests\PersonaFormRequest;
use DB;
use sisMR\Persona;
use Fpdf;
use Illuminate\Support\Facades\Auth;

class VendedoresController extends Controller
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
            $personas=DB::table('persona')
            ->where('nombre','LIKE','%'.$query.'%')
            ->where ('tipo_persona','=','vendedores')
            ->orwhere('num_documento','LIKE','%'.$query.'%')
            ->where ('tipo_persona','=','vendedor')
            ->orderBy('idpersona','desc')
            ->paginate(7);
            return view('ventas.vendedores.index',["personas"=>$personas,"searchText"=>$query]);
        }
    }
     public function create()
    {
        return view("ventas.vendedores.create");
    }
    public function store (PersonaFormRequest $request)
    {
        $persona=new Persona;
        $persona->tipo_persona='vendedor';
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');        
        $persona->save();
        return Redirect::to('ventas/vendedores');

    }
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view("ventas.vendedores.edit",["persona"=>Persona::findOrFail($id)]);
    }
     
    public function update(PersonaFormRequest $request,$id)
    {
        $persona=Persona::findOrFail($id);

        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');

        $persona->update();
        return Redirect::to('ventas/vendedores');
    }
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo_persona='Inactivo';
        $persona->update();
        return Redirect::to('ventas/vendedores');
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('persona')
            ->where ('tipo_persona','=','vendedor')
            ->orderBy('idpersona','desc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Vendedores"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(80,8,utf8_decode("Nombre"),1,"","L",true);
         $pdf::cell(35,8,utf8_decode("Documento"),1,"","L",true);
         $pdf::cell(50,8,utf8_decode("Email"),1,"","L",true);
         $pdf::cell(25,8,utf8_decode("Tel??fono"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(80,6,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(35,6,utf8_decode($reg->num_documento),1,"","L",true);
            $pdf::cell(50,6,utf8_decode($reg->email),1,"","L",true);
            $pdf::cell(25,6,utf8_decode($reg->telefono),1,"","L",true);
            $pdf::Ln(); 
         }

          // Obtiene el objeto del Usuario Autenticado
          $user = Auth::user();
          //obener el id y cargar en una variable
          $usuarioActual = $user->id;
          $ruta = 'pdf/'.$usuarioActual.'_reportevendedores.pdf';
          $pdf::Ln();
          $pdf::Output($ruta, 'F');
          return view('ventas.vendedores.vistapdf');
    }
}

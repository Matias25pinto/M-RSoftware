<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;
use sisMR\Http\Requests\CFacturaFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Collection;
use DB;
use sisMR\Factura;
use Illuminate\Support\Facades\Auth;
use Response;
use Excel;

class Cventascontroller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth',
            'verificarPermiso:administrador,contador'
            ]);
    }
    public function index(Request $request)
    {
        if ($request)
        {
           $query=trim($request->get('searchText'));
           $facturas=DB::table('factura as f')
            ->join('persona as p','f.idcliente','=','p.idpersona')
            ->select('f.idfactura','f.fecha_hora','p.nombre','f.estado','f.nro_factura','f.tipo_documento')
            ->where('f.nro_factura','LIKE','%'.$query.'%')
            ->orwhere('p.nombre','LIKE','%'.$query.'%')
            ->orderBy('f.idfactura','desc')
            ->paginate(10);
            return view('cargar.ventas.index',["facturas"=>$facturas,"searchText"=>$query]);

        }
    }
    public function create()
    {   
        $personas=DB::table('persona')->get();
    	
        return view("cargar.ventas.create",["personas"=>$personas]);
    }
    public function store (CFacturaFormRequest $request)
    {
     
                //CREAR FACTURA
                    
                    $exentas = $request->get('exentas');
                    $impuesto5 = $request->get('impuesto5');
                    $impuesto10 = $request->get('impuesto10');
                    $total_venta =  $exentas +  $impuesto5 + $impuesto10;
               
                    $factura = new Factura;
                    $factura->idventa = 0;
                    $factura->nro_factura = $request->get('nro_factura');
                    $factura->timbrado = $request->get('timbrado');
                    $factura->idcliente = $request->get('cliente');
                    $factura->exentas = $request->get('exentas');
                    $factura->impuesto5 = $request->get('impuesto5');
                    $factura->impuesto10 = $request->get('impuesto10');
                    $factura->total_venta =  $total_venta;
                    $factura->fecha_hora = $request->get('fechaInicio');
                    $factura->estado = "ACTIVO";
                    $tipo_comprobante = $request->get('tipo_comprobante');
                    if($tipo_comprobante === 'Factura'){
                        $factura->tipo_documento = 1;
                    }elseif($tipo_comprobante === 'NotaCredito-compras'){
                        $factura->tipo_documento = 3;
                    }
                    $factura->save();
            
          
       
            $idfactura =  $factura->idfactura;
        
            return Redirect::action('Cventascontroller@show',$idfactura);
    }

    public function show($id)
    {
    	$factura=DB::table('factura as f')
            ->join('persona as p','f.idcliente','=','p.idpersona')
            ->select('f.idfactura','p.nombre', 'p.num_documento','p.direccion','p.telefono','f.fecha_hora','f.nro_factura','f.timbrado','f.exentas','f.impuesto5','f.impuesto10','f.total_venta')
            ->where('f.idfactura','=',$id)
            ->first();
        return view("cargar.ventas.show",["factura"=>$factura]);
    }
    public function edit($id)
    {
        $factura=DB::table('factura as f')
        ->join('persona as p','f.idcliente','=','p.idpersona')
        ->select('f.idfactura','f.idcliente','p.nombre', 'p.num_documento','p.direccion','p.telefono','f.fecha_hora','f.nro_factura','f.timbrado','f.exentas','f.impuesto5','f.impuesto10','f.total_venta')
        ->where('f.idfactura','=',$id)
        ->first();
        
        return view("cargar.ventas.edit",["factura"=>$factura]);
    }
    
    
    public function update(CFacturaFormRequest $request)
    {
        $exentas = $request->get('exentas');
        $impuesto5 = $request->get('impuesto5');
        $impuesto10 = $request->get('impuesto10');
        $total_venta =  $exentas +  $impuesto5 + $impuesto10;
        $id= $request->get('idfactura');
        $factura=Factura::findOrFail($id);
                    $factura->nro_factura = $request->get('nro_factura');
                    $factura->timbrado = $request->get('timbrado');
                    $factura->idcliente = $request->get('cliente');
                    $factura->exentas = $request->get('exentas');
                    $factura->impuesto5 = $request->get('impuesto5');
                    $factura->impuesto10 = $request->get('impuesto10');
                    $factura->total_venta =  $total_venta;
                    $factura->fecha_hora = $request->get('fechaInicio');
        $factura->update();
        return Redirect::to('cargar/ventas');
    }
    public function destroy($id)
    {
        $factura=Factura::findOrFail($id);
        $factura->estado='ANULADO';
        $factura->update();
        return Redirect::to('cargar/ventas');
    }

    
}

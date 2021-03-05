<?php 

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Http\Requests\VentaFormRequest;
use sisMR\Http\Requests\CobrarFormRequest;
use sisMR\Venta;
use sisMR\Factura;
use sisMR\Ticket;
use sisMR\DetalleVenta;
use sisMR\Articulo;
use sisMR\User;

use DB;
use Fpdf;
use NumerosEnLetras;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth',
            'verificarPermiso:administrador,ventas'
            ]);
    }
    public function index(Request $request)
    {
        if ($request){
            if (Auth::user()->permisos == "administrador") {
            $query=trim($request->get('searchText'));
            $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
            ->where('v.idventa','LIKE','%'.$query.'%')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
            ->paginate(10);
            }else{
                $idusers = Auth::user()->id;
             $query=trim($request->get('searchText'));
            $ventas=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
            ->where('v.idventa','LIKE','%'.$query.'%')
            ->where('v.idusers','=',$idusers)
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
            ->paginate(10);
            }
          
            return view('ventas.venta.index',["ventas"=>$ventas,"searchText"=>$query]);

        }
    }
    public function create()
    {      
        $personas=DB::table('persona')->where('tipo_persona','=','Cliente')->get();
        $vendedores=DB::table('persona')->where('tipo_persona','=','vendedor')->get();
    	$articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo) AS articulo'),'art.idarticulo','art.stock','art.impuesto','art.precio_venta','art.precio_venta2','art.precio_venta3','art.unidad_medida','art.nombre')
            ->where('art.estado','=','Activo')
            ->where('art.precio_venta','>','0')
            ->orwhere('art.stock','>','0')
            ->groupBy('art.idarticulo','articulo','art.stock','art.impuesto','art.unidad_medida','art.imagen')
            ->get();
        return view("ventas.venta.create",["articulos"=>$articulos,"personas"=>$personas,"vendedores"=>$vendedores]);
    }
     public function store (VentaFormRequest $request)
    {
            // Obtiene el objeto del Usuario Autenticado
            $user = Auth::user();
            //obtener los datos del talonario
            $nro_talonario = $user->nro_talonario;
            //se obtiene el numero de caja
            $nro_caja = $user->idcaja;
            DB::beginTransaction();
        	$venta=new Venta;
	        $venta->idcliente=$request->get('idcliente');
            $venta->idvendedor=$request->get('idvendedor');
            $venta->tipo_comprobante=$request->get('tipo_comprobante');
            $venta->importe=$request->get('importe');
            $venta->idusers=$request->get('idusers');
            $venta->idcaja= $nro_caja;
	        $venta->serie_comprobante=0;
	        $venta->num_comprobante=0;
	        $venta->total_venta=$request->get('total_venta');
	        $mytime = Carbon::now('America/Asuncion');
	        $venta->fecha_hora=$mytime->toDateTimeString();
	        $venta->estado='ACTIVO';
            $venta->save();

	        $idarticulo = $request->get('idarticulo');
	        $cantidad = $request->get('cantidad');
            $unidad_medida = $request->get('unidad_medida');
	        $descuento = 0;
	        $precio_venta = $request->get('precio_venta');
            $impuesto = $request->get('impuesto');
            if($venta->tipo_comprobante == "Factura"){
                 //obtiene el ultimo idfactura
                $ultima_factura = DB::table('factura as f')->select('f.idfactura')->orderBy('f.idfactura', 'desc')->first();
                //en caso que sea primera factura
                //esto solo funciona si la base de datos es nueva y empieza en 1 factura
                //si empieza con otro valor da error al momento de imprimir detalle venta det
                if( is_null($ultima_factura )){
                    $ultima_factura = 1;
                }else{
                    $ultima_factura = $ultima_factura->idfactura + 1;
                }
                $axicant=count($idarticulo);
                $cont_factura = 0;
                $exentas = 0;
                $impuesto5 = 0;
                $impuesto10 = 0;
                $total_factura = 0;
                //obtener el indice de factura
                $indice_factura = $user->indice_factura;
                $indice_factura = $indice_factura;
                //obtener timbrado
                $timbrado = $user->timbrado;
            }else{
                $axicant=count($idarticulo);
                $cont_factura = 0;
                $exentas = 0;
                $impuesto5 = 0;
                $impuesto10 = 0;
                $total_factura = 0;
                $ultima_factura = 0;
            }
            
            for ($cont=0; $cont < $axicant; $cont++) { 
                $detalle = new DetalleVenta();
                $detalle->idventa= $venta->idventa; 
                $detalle->idfactura = $ultima_factura; 
                $detalle->idarticulo= $idarticulo[$cont];
                $detalle->cantidad= $cantidad[$cont];
                $detalle->descuento=0;
                $detalle->unidad_medida=$unidad_medida[$cont];
                $detalle->precio_venta= $precio_venta[$cont];
                $detalle->subtotal= 0;
                $detalle->impuesto= $impuesto[$cont];
                $detalle->save();
                if( $cont_factura < 15){
                    if($impuesto[$cont] == 0){
                        if($detalle->unidad_medida == 'unidad' OR $detalle->unidad_medida == 'servicio'){
                            $exentas = $exentas + ($precio_venta[$cont] * $cantidad[$cont]);
                        }
                        if($detalle->unidad_medida == 'gramos'){
                            $exentas = $exentas + ($precio_venta[$cont] * $cantidad[$cont])/1000;
                        }
                    }
                    if($impuesto[$cont] == 5){
                        if($detalle->unidad_medida == 'unidad' OR $detalle->unidad_medida == 'servicio'){
                            $impuesto5 = $impuesto5 + ($precio_venta[$cont] * $cantidad[$cont]);
                        }
                        if($detalle->unidad_medida == 'gramos'){
                            $impuesto5 = $impuesto5 + ($precio_venta[$cont] * $cantidad[$cont])/1000;
                        }
                    }
                    if($impuesto[$cont] == 10){
                        if($detalle->unidad_medida == 'unidad' OR $detalle->unidad_medida == 'servicio'){
                            $impuesto10 = $impuesto10 + ($precio_venta[$cont] * $cantidad[$cont]);
                        }
                        if($detalle->unidad_medida == 'gramos'){
                            $impuesto10 = $impuesto10 + ($precio_venta[$cont] * $cantidad[$cont])/1000;
                        }
                    }
                    if($detalle->unidad_medida == 'unidad' OR $detalle->unidad_medida == 'servicio'){
                        $total_factura = $total_factura + ($precio_venta[$cont] * $cantidad[$cont]);
                    }
                    if($detalle->unidad_medida == 'gramos'){
                        $total_factura = $total_factura + ($precio_venta[$cont] * $cantidad[$cont])/1000;
                    }

                    $cont_factura = $cont_factura + 1;
                }
                if($cont == $axicant-1){
                    $cont_factura = 15;
                }
                //CREAR FACTURA
                if($venta->tipo_comprobante == "Factura" && $cont_factura === 15){
                    //str_pad agrega 0 por la izquierda
                    $str_factura = str_pad($indice_factura, 7, "0", STR_PAD_LEFT);
                    $nro_factura = $nro_talonario.'-'.$str_factura;
                    $factura = new Factura;
                    $factura->idventa = $venta->idventa;
                    $factura->idcliente = $venta->idcliente;
                    $factura->nro_factura = $nro_factura;
                    $factura->timbrado = $timbrado;
                    $factura->exentas = $exentas;
                    $factura->impuesto5 = $impuesto5;
                    $factura->impuesto10 = $impuesto10;
                    $factura->total_venta = $total_factura;
                    $factura->fecha_hora =$venta->fecha_hora;
                    $factura->estado = "ACTIVO";
                    $factura->save();

                    $exentas = 0;
                    $impuesto5 = 0;
                    $impuesto10 = 0;

                    $cont_factura = 0;
                    $total_factura = 0;
                    //actualiza el id factura
                    $ultima_factura = $ultima_factura + 1;
                    $indice_factura = $indice_factura + 1;
                     
                }
            }
            if($venta->tipo_comprobante == "Factura"){
                //actualizacion del nro_factura
                $id_usuario = $user->id;
                $actualizar_indice=User::findOrFail($id_usuario);
                $actualizar_indice->indice_factura=$indice_factura;
                $actualizar_indice->update();
            }
           
           
            if ($venta->tipo_comprobante == "Ticket") {
                $ticket = new Ticket;
                $ticket->idventa = $venta->idventa;
                $ticket->total_venta = $venta->total_venta;
                $ticket->fecha_hora =$venta->fecha_hora;
                $ticket->estado = $venta->estado;
                $ticket->save();   

            }

            DB::commit();

       
        if ($venta->tipo_comprobante == "Factura") {
        
            return Redirect::action('VentaController@reportec',$venta->idventa);

        }
        if ($venta->tipo_comprobante == "Ticket") {
        
            return Redirect::action('VentaController@ticket',$venta->idventa);

        }

        
    }
    
    public function show($id)
    {
    	$venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','p.tipo_documento','p.num_documento','p.direccion','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
            ->where('v.idventa','=',$id)
            ->first();
        $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.descuento', 'd.precio_venta','a.impuesto','d.unidad_medida')
            ->where('d.idventa','=',$id)
            ->get();
        $tipo_comprobante=$venta->tipo_comprobante;
        if ( $tipo_comprobante=="Ticket") {
            $comprobante=DB::table('ticket as t')
                ->select('fecha_hora',DB::raw('lpad(t.idventa, 6, 0) as idventa'), DB::raw('lpad(t.idticket, 6, 0) as comprobante'))
                ->where('t.idventa','=',$id)
                ->first();
        }
        elseif ($venta->tipo_comprobante == "Factura") {
            $comprobante=DB::table('factura as t')
                ->select('fecha_hora', DB::raw('lpad(t.idventa, 6, 0) as idventa'),  DB::raw('lpad(t.idfactura, 6, 0) as comprobante'))
                ->where('t.idventa','=',$id)
                ->first();
        }

        $impuesto10_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta* dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','10')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto10_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','10')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();
         $impuesto5_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta * dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','5')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto5_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','5')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();
        $impuesto0_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta * dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','0')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto0_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','0')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();


        return view("ventas.venta.show",["venta"=>$venta,"detalles"=>$detalles,"impuesto10_unidad"=>$impuesto10_unidad,"impuesto10_gramos"=>$impuesto10_gramos,"impuesto5_unidad"=>$impuesto5_unidad,"impuesto5_gramos"=>$impuesto5_gramos,"impuesto0_unidad"=>$impuesto0_unidad,"impuesto0_gramos"=>$impuesto0_gramos,"comprobante"=>$comprobante]);
    }

    public function destroy($id)
    {

        $condicion=Venta::findOrFail($id);
        if ($condicion->estado == 'ACTIVO') {
            $venta=Venta::findOrFail($id);
            $venta->estado='ANULADO';
            $venta->update();
        }
        //anular todas las facturas relacionadas a la venta
        if ($condicion->estado == 'ACTIVO') {
             //Obtengo los datos
             $facturas=DB::table('factura as f')
            ->join('venta as v','f.idventa','=','v.idventa')
            ->select('f.idfactura')
            ->where('v.idventa','=',$id)
            ->get();
            foreach ($facturas as $factura) {
                $factura=Factura::findOrFail($factura->idfactura);
                $factura->estado='ANULADO';
                $factura->update();
            }
           
        }
        return Redirect::to('ventas/venta');
    }
    public function ticket($id){
     
        $venta=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idvendedor','v.idventa','v.fecha_hora','p.nombre','p.tipo_documento','p.num_documento','p.direccion','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta','importe')
            ->where('v.idventa','=',$id)
            ->first();
        $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
            ->select('a.nombre as articulo', 'd.cantidad', 'd.descuento', 'd.precio_venta','a.impuesto','d.unidad_medida')
            ->where('d.idventa','=',$id)
            ->get();
        $tipo_comprobante=$venta->tipo_comprobante;
        if ( $tipo_comprobante=="Ticket") {
            $comprobante=DB::table('ticket as t')
                ->select('fecha_hora',DB::raw('lpad(t.idventa, 6, 0) as idventa'), DB::raw('lpad(t.idticket, 6, 0) as comprobante'))
                ->where('t.idventa','=',$id)
                ->first();
        }
        elseif ($venta->tipo_comprobante == "Factura") {
            $comprobante=DB::table('factura as t')
                ->select('fecha_hora', DB::raw('lpad(t.idventa, 6, 0) as idventa'),  DB::raw('lpad(t.idfactura, 6, 0) as comprobante'))
                ->where('t.idventa','=',$id)
                ->first();
        }
        $idvendedor = $venta->idvendedor;
        $vendedor = DB::table('persona as p')
            ->select('p.idpersona','p.nombre')
            ->where('p.idpersona','=',$idvendedor)
            ->first();
        $impuesto10_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta* dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','10')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto10_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','10')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();
         $impuesto5_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta * dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','5')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto5_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','5')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();
        $impuesto0_unidad=DB::table('detalle_venta as dv')
            ->select(DB::raw('sum(dv.precio_venta * dv.cantidad) as precio_venta'))
            ->where('dv.impuesto','=','0')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','!=',"gramos")
            ->groupBy()
            ->first();
        $impuesto0_gramos=DB::table('detalle_venta as dv')
            ->select(DB::raw('(sum(dv.precio_venta* dv.cantidad)/1000) as precio_venta'))
            ->where('dv.impuesto','=','0')
            ->where('dv.idventa','=',$id)
            ->where('dv.unidad_medida','=',"gramos")
            ->groupBy()
            ->first();


        return view("ventas.venta.ticket",["venta"=>$venta,"detalles"=>$detalles,"impuesto10_unidad"=>$impuesto10_unidad,"impuesto10_gramos"=>$impuesto10_gramos,"impuesto5_unidad"=>$impuesto5_unidad,"impuesto5_gramos"=>$impuesto5_gramos,"impuesto0_unidad"=>$impuesto0_unidad,"impuesto0_gramos"=>$impuesto0_gramos,"comprobante"=>$comprobante, "vendedor"=>$vendedor]);
        
    }
    public function reportec($id){
       //Obtengo los datos
        $facturas=DB::table('factura as f')
        ->join('persona as p','f.idcliente','=','p.idpersona')
        ->join('venta as v','f.idventa','=','v.idventa')
        ->select('idfactura','v.idventa','v.fecha_hora','p.nombre','p.direccion','p.num_documento','v.tipo_comprobante',
            'f.nro_factura','f.exentas','f.impuesto5','f.impuesto10','f.total_venta','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
        ->where('v.idventa','=',$id)
        ->get();
 
        foreach ($facturas as $factura) {
    
            $detalles=DB::table('detalle_venta as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->join('factura as f','d.idfactura','=','f.idfactura')
            ->select('a.nombre as articulo','d.cantidad','d.descuento','d.precio_venta','a.unidad_medida', 'a.impuesto',
            'f.exentas', 'f.impuesto5', 'f.impuesto10', 'f.total_venta')
            ->where('d.idfactura','=',$factura->idfactura)
            ->get();
   
            $pdf=new FPDF('P','cm','Legal');
            $pdf::AddPage('P','Legal'); 
            $pdf::SetFont('Arial','B',8);
            //ENCABEZADO 
            //ORIGINAL
            $pdf::SetXY(160,33);
            $pdf::Cell(0,0,utf8_decode($factura->nro_factura));
            //DUPLICADO
            $pdf::SetXY(160,185);
            $pdf::Cell(0,0,utf8_decode($factura->nro_factura));
            //convertir fecha
            $newDate = date("d/m/Y", strtotime($factura->fecha_hora));
            //ORIGINAL
            $pdf::SetXY(44,45);
            $pdf::Cell(0,0,substr($newDate,0,10));
            //DUPLICADO
            $pdf::SetXY(44,197);
            $pdf::Cell(0,0,substr($newDate,0,10));

            //ORIGINAL
            $pdf::SetXY(162,46);
            $pdf::Cell(0,0,utf8_decode("X"));

            //DUPLICADO
            $pdf::SetXY(162,198);
            $pdf::Cell(0,0,utf8_decode("X"));
     
            //ORIGINAL
            $pdf::SetXY(53,51.5);
            $pdf::Cell(0,0,utf8_decode($factura->nombre));

            //DUPLICADO
            $pdf::SetXY(53,203.5);
            $pdf::Cell(0,0,utf8_decode($factura->nombre));

            //ORIGINAL
            $pdf::SetXY(25,57.5);
            $pdf::Cell(0,0,utf8_decode($factura->num_documento));

            //DUPLICADO
            $pdf::SetXY(25,209.5);
            $pdf::Cell(0,0,utf8_decode($factura->num_documento));
            //VARIABLES DE CONTROL TOTAL Y ALTURA
            $total=0;
            # fin del encabezado
            //VARIABLES DE CONTROL DE IMPUESTO, EMBACEZADO Y PIE DE PAGINA
            $y=74.5;
            $y2=224.5;
            $contador = 0;
            $imprimir_pie_de_pagina = 0;
            $impuestoEx = 0;
            $impuesto5 = 0;
            $impuesto10 = 0;
            $cerrar_foreach = 0;
            $controlar_terminado = 0;
            foreach($detalles as $cont):
                $cerrar_foreach = $cerrar_foreach + 1;
            endforeach;
            foreach ($detalles as $det){

                //imprimir impuestos Exentas
                if($det->impuesto == 0):
                    if($det->unidad_medida == 'unidad' || $det->unidad_medida == 'servicio'):
                 
                        //ORIGINAL
                        $pdf::SetXY(123,$y);
                        $pdf::MultiCell(25,0,number_format(($det->precio_venta*$det->cantidad), 0, ",", "."));

                        //DUPLICADO
                        $pdf::SetXY(123,$y2);
                        $pdf::MultiCell(25,0,number_format(($det->precio_venta*$det->cantidad), 0, ",", "."));

                        $impuestoEx = $impuestoEx + ($det->precio_venta*$det->cantidad);
                    endif;
                    if($det->unidad_medida == 'gramos'):
                        //ORIGINAL
                 
                        $pdf::SetXY(133,$y);
                        $pdf::MultiCell(25,0,$pdf::MultiCell(25,0,number_format((($det->precio_venta*$det->cantidad)/1000), 0, ",", ".")));

                        //DUPLICADO
                        $pdf::SetXY(133,$y2);
                        $pdf::MultiCell(25,0,$pdf::MultiCell(25,0,number_format((($det->precio_venta*$det->cantidad)/1000), 0, ",", ".")));

                        $impuestoEx = $impuestoEx + (($det->precio_venta*$det->cantidad)/1000);
                    endif;
                endif;
                //imprimir impuesto 5%
                if($det->impuesto == 5):
                    if($det->unidad_medida == 'unidad' || $det->unidad_medida == 'servicio'):
                 
                        //ORIGINAL
                        $pdf::SetXY(156,$y);
                        $pdf::MultiCell(25,0,number_format($det->precio_venta*$det->cantidad, 0, ",", "."));
  
                        //DUPLICADO
                        $pdf::SetXY(156,$y2);
                        $pdf::MultiCell(25,0,number_format($det->precio_venta*$det->cantidad, 0, ",", "."));
  
                        $impuesto5 = $impuesto5 + ($det->precio_venta*$det->cantidad);
                    endif;
                    if($det->unidad_medida == 'gramos'):
                 
                        //ORIGINAL
                
                        $pdf::SetXY(151,$y);
                        $pdf::MultiCell(25,0,number_format((($det->precio_venta*$det->cantidad)/1000), 0, ",", "."));
  
                        //DUPLICADO
                        $pdf::SetXY(151,$y2);
                        $pdf::MultiCell(25,0,number_format((($det->precio_venta*$det->cantidad)/1000), 0, ",", "."));
  
                        $impuesto5 = $impuesto5 + (($det->precio_venta*$det->cantidad)/1000);
                    endif;
                endif;
                //imprimir impuesto 10%
                if($det->impuesto == 10):
            
                    if($det->unidad_medida == 'unidad' || $det->unidad_medida == 'servicio'):
                        //ORIGINAL
                        $pdf::SetXY(183,$y);
                        $pdf::MultiCell(25,0, number_format(($det->precio_venta*$det->cantidad), 0, ",", "."));
  
                        //DUPLICADO
                        $pdf::SetXY(183,$y2);
                        $pdf::MultiCell(25,0, number_format(($det->precio_venta*$det->cantidad), 0, ",", "."));
  
                        $impuesto10 = $impuesto10 + ($det->precio_venta*$det->cantidad);
                    endif;
                    if($det->unidad_medida == 'gramos'):
                 
                        //ORIGINAL
                        $pdf::SetXY(183,$y);
                        $pdf::MultiCell(25,0,number_format(($det->precio_venta*$det->cantidad)/1000, 0, ",", "."));
  
                        //DUPLICADO
                  
                        $pdf::SetXY(183,$y2);
                        $pdf::MultiCell(25,0,number_format(($det->precio_venta*$det->cantidad)/1000, 0, ",", "."));
                        $impuesto10 = $impuesto10 + (($det->precio_venta*$det->cantidad)/1000);
                    endif;
                endif;
                # imprimir contenido de grilla
      
                //controlar terminado de foreach
                $controlar_terminado = $controlar_terminado +1;
                //IMPRIMIR LISTA DE ARTICULOS
                $total=$total+($det->precio_venta*$det->cantidad);
                $contador = $contador + 1;

                //ORIGINAL
         
                $pdf::SetXY(21,$y);
                $pdf::MultiCell(25,0,number_format($det->cantidad, 0, ",", "."));

                //DUPLICADO
                $pdf::SetXY(21,$y2);
                $pdf::MultiCell(25,0,number_format($det->cantidad, 0, ",", "."));

                //ORIGINAL
                $pdf::SetXY(35,$y);
                $pdf::MultiCell(50,0,utf8_decode($det->articulo));

                //DUPLICADO
                $pdf::SetXY(35,$y2);
                $pdf::MultiCell(50,0,utf8_decode($det->articulo));

                //ORIGINAL
         
                $pdf::SetXY(116,$y);
                $pdf::MultiCell(25,0,number_format($det->precio_venta, 0, ",", "."));

                //DUPLICADO
                $pdf::SetXY(116,$y2);
                $pdf::MultiCell(25,0,number_format($det->precio_venta, 0, ",", "."));
                $y=$y+4;
                $y2=$y2+4;
            }

            //IMPRIMIR PIE DE PAGINA
            $impuestoEx = 0;
            $impuesto5 = 0;
            $impuesto10 = 0;

            //ORIGINAL
      
            $pdf::SetXY(133,138);
            $pdf::Cell(25,0,number_format(($det->exentas), 0, ",", "."));

            //DUPLICADO
            $pdf::SetXY(133,289.5);
            $pdf::Cell(20,0,number_format(($det->exentas), 0, ",", "."));

            //ORIGINAL
     
            $pdf::SetXY(157,138);
            $pdf::Cell(20,0,number_format(($det->impuesto5), 0, ",", "."));

            //DUPLICADO
            $pdf::SetXY(157,289.5);
            $pdf::Cell(20,0,number_format(($det->impuesto5), 0, ",", "."));
            //ORIGINAL 
            $pdf::SetXY(180,138);
            $pdf::Cell(20,0,number_format(($det->impuesto10), 0, ",", "."));

            //DUPLICADO
     
            $pdf::SetXY(180,289.5);
            $pdf::Cell(20,0,number_format(($det->impuesto10), 0, ",", "."));
            //ORIGINAL
            $pdf::SetXY(180,143);
            $pdf::Cell(20,0,number_format(($det->total_venta), 0, ",", "."));
            //DUPLICADO
            $pdf::SetXY(180,295);
            $pdf::Cell(20,0,number_format(($det->total_venta), 0, ",", "."));

            /*CONVERTIR NUMEROS EN LETRAS*/ 

            //ORIGINAL
      
            $pdf::SetXY(56,143);
            $pdf::Cell(30,0,NumerosEnLetras :: convertir ( ($det->total_venta) ));

            //DUPLICADO
            $pdf::SetXY(56,295);
            $pdf::Cell(30,0,NumerosEnLetras :: convertir ( ($det->total_venta) ));

            //calcular iva
            $iva5 = round($det->impuesto5/21, 0, PHP_ROUND_HALF_UP);
            $iva10 = round($det->impuesto10/11, 0, PHP_ROUND_HALF_UP);

            /*CALCULAR IVA 5%*/ 

            //ORIGINAL
      
            $pdf::SetXY(50,149);
            $pdf::Cell(20,0,number_format($iva5, 0, ",", "."));

            //DUPLICADO
            $pdf::SetXY(50,301);
            $pdf::Cell(20,0,number_format($iva5, 0, ",", "."));

            /*CALCULAR IVA 10%*/ 

            //ORIGINAL
      
            $pdf::SetXY(101,149);
            $pdf::Cell(20,0,number_format($iva10, 0, ",", "."));

            //DUPLICADO
            $pdf::SetXY(101,301);
            $pdf::Cell(20,0,number_format($iva10, 0, ",", "."));

            /*CALCULAR TOTAL IVA */ 

            //ORIGINAL
      
            $pdf::SetXY(157,149);
            $pdf::Cell(20,0,number_format($iva10+$iva5, 0, ",", "."));

            //DUPLICADO
            $pdf::SetXY(157,301);
            $pdf::Cell(20,0,number_format($iva10+$iva5, 0, ",", "."));
        }
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        //obener el id y cargar en una variable
        $usuarioActual = $user->id;
        $ruta = 'factura/'.$usuarioActual.'_factura.pdf';
        $pdf::Ln();
        $pdf::Output($ruta, 'F');
        return view('ventas.venta.factura');
    }

    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
            ->orderBy('v.idventa','desc')
            ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
            ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Ventas"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(35,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Cliente"),1,"","L",true);
         $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
         $pdf::cell(25,8,utf8_decode("Total"),1,"","R",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(80,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(45,8,utf8_decode($reg->tipo_comprobante),1,"","L",true);
            $pdf::cell(25,8,utf8_decode($reg->total_venta),1,"","R",true);
            $pdf::Ln(); 
         }
         $pdf::Output();
         exit;
    }
}

<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisMR\Http\Requests;
use sisMR\Http\Requests\PagoFormRequest;
use sisMR\Pago;
use DB;
use Carbon\Carbon;
use Fpdf;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth',
            'verificarPermiso:administrador'
            ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            if (Auth::user()->permisos == "administrador") {
                if($request){
    		      $query=trim($request->get('searchText'));
    		      $pagos=DB::table('pago_salario as p')
    		      ->join('persona as per', 'per.idpersona','=','p.idusers')
                  ->select('p.idpago','p.fecha_pago','p.monto_pagado','p.estado','p.comision','per.nombre')
    		      ->orderBy('p.fecha_pago','desc')
    		      ->paginate(10);
    		
                }
            }   
        return view("pago.salario.index",["pagos"=>$pagos,"searchText"=>$query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendedor=DB::table('persona')->where('tipo_persona','=','vendedor')->get();
        return view("pago.salario.create",["vendedor"=>$vendedor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            
            DB::beginTransaction();
            $pago_salario = new Pago;
            $pago_salario->idusers = $request->get('idusers');
            $pago_salario->comision= $request->get('comision');
            $pago_salario->fecha_inicio=$request->get('fechaInicio');
            $pago_salario->fecha_final=$request->get('fechaFin');
            $pago_salario->estado='Activo';
            
            //para obtener la suma de los dias trabajados
            $fechaInicio=$request->get('fechaInicio');
            $fechaFin=$request->get('fechaFin');
            $idusers=$request->get('idusers');
            $comision=$request->get('comision');
            if ($fechaInicio == $fechaFin) {
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();
        }else{
            $mytime = Carbon::now('America/Asuncion');
            $fechaActual = $mytime->addDay(1);
            $fechaActualMasUno = $fechaActual->toDateString();

            $mytime2 = Carbon::createFromFormat('Y-m-d', $fechaFin, 'America/Asuncion');
            $fechaFin2= $mytime2->addDay(1);
            $fechaFinMasUno = $fechaFin2->toDateString();

            if ($fechaActualMasUno == $fechaFinMasUno) {
            //Obtenemos los registros
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFinMasUno))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFinMasUno))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();
            }
            else{
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();

            }
        }   
            foreach ($ventas as $ven)
            {
                $monto_pagado=$ven->total*$comision/100;
            }
            
            $pago_salario->monto_pagado=$monto_pagado;
            $mytime = Carbon::now('America/Asuncion');
    		$pago_salario->fecha_pago=$mytime->toDateTimeString();
            $pago_salario->save();
        DB::commit();
        } catch (Exception $e) 
        {
            DB::rollback();
        }

        return Redirect::action('PagoController@reporte',['$fechaInicio'=>$fechaInicio,'$fechaFin'=>$fechaFin,'$idusers'=>$idusers,'comision'=>$comision]);
    }
    public function reporte($fechaInicio,$fechaFin,$idusers,$comision){
        //para obtener la suma de los dias trabajados
             $users=DB::table('persona')->where('idpersona','=',$idusers)->get();
            if ($fechaInicio == $fechaFin) {
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();
        }else{
            $mytime = Carbon::now('America/Asuncion');
            $fechaActual = $mytime->addDay(1);
            $fechaActualMasUno = $fechaActual->toDateString();

            $mytime2 = Carbon::createFromFormat('Y-m-d', $fechaFin, 'America/Asuncion');
            $fechaFin2= $mytime2->addDay(1);
            $fechaFinMasUno = $fechaFin2->toDateString();

            if ($fechaActualMasUno == $fechaFinMasUno) {
            //Obtenemos los registros
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFinMasUno))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFinMasUno))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();
            }
            else{
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
                ->where('v.idvendedor','=',$idusers)
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();

            }
        }
        //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
        foreach ($users as $us)
         {
                 $pdf::Cell(0,10,utf8_decode("Nombre: ".$us->nombre),0,"","C");
                 $pdf::Ln();
                 if ($fechaInicio == $fechaFin) {
                     $pdf::Cell(0,10,utf8_decode("Pago de fecha ".$fechaInicio),0,"","C");
                     $pdf::Ln();
                 }else{
                     $pdf::Cell(0,10,utf8_decode("Periodo de pago del ".$fechaInicio." al ".$fechaFin),0,"","C");
                     $pdf::Ln();
                 }
                 
            }
         foreach ($ventas as $ven)
         {
             $pdf::Cell(0,10,utf8_decode("Total de Ventas: ".number_format($ven->total,0,'','.')."Gs."),0,"","C");
             $monto_pagado=$ven->total*$comision/100;
             $pdf::Ln();
             $pdf::Cell(0,10,utf8_decode("Total a cobrar: ".number_format($monto_pagado,0,'','.')."Gs."),0,"","C");
            }

         $pdf::Ln();
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190  
         $pdf::cell(15,8,utf8_decode("Venta"),1,"","L",true);      
         $pdf::cell(32,8,utf8_decode("Fecha   -   Hora"),1,"","L",true);
         $pdf::cell(50,8,utf8_decode("Cliente"),1,"","L",true);
         $pdf::cell(25,8,utf8_decode("Comprobante"),1,"","L",true);
         $pdf::cell(30,8,utf8_decode("Monto de Venta"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Com. %"),1,"","R",true);
         $pdf::cell(30,8,utf8_decode("Monto a cobrar"),1,"","R",true);
        
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(15,8,utf8_decode($reg->idventa),1,"","L",true);
            $pdf::cell(32,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(50,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(25,8,utf8_decode($reg->tipo_comprobante),1,"","L",true);
            $pdf::cell(30,8,number_format($reg->total_venta,0,'','.'),1,"","R",true);
            $pdf::cell(15,8,utf8_decode($comision."%"),1,"","L",true);
            $pdf::cell(30,8,number_format($reg->total_venta*$comision/100,0,'','.'),1,"","R",true);
            $pdf::Ln();
            
         }
         foreach ($ventas as $ven)
         {
            $pdf::cell(50,8,"Total de ventas Gs./ ".number_format($ven->total,0,'','.'),1,"","R",true);
            $pdf::Ln();
            $pdf::cell(50,8,utf8_decode("Total comisión Gs./ ").number_format($monto_pagado,0,'','.'),1,"","R",true);
            }
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        //obener el id y cargar en una variable
        $usuarioActual = $user->id;
        $ruta = 'pdf/'.$usuarioActual.'_reportepagocomision.pdf';
        $pdf::Ln();
        $pdf::Output($ruta, 'F');
        return view('pago.salario.vistapdf');
        }
    public function detalles($idpago){
        //para obtener la suma de los dias trabajados
            $pago_salario=DB::table('pago_salario')
            ->where('idpago','=',$idpago)
            ->first();
                $fechaInicio = $pago_salario->fecha_inicio;
                $fechaFin = $pago_salario->fecha_final;
                $idusers = $pago_salario->idusers;
                $comision = $pago_salario->comision;
            //redireccionar al reporte
            return Redirect::action('PagoController@reporte',['$fechaInicio'=>$fechaInicio,'$fechaFin'=>$fechaFin,'$idusers'=>$idusers,'comision'=>$comision]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $condicion=Pago::findOrFail($id);
        if ($condicion->estado == 'Activo') {
            $Pago=Pago::findOrFail($id);
            $Pago->estado='Anulado';
            $Pago->update();
        }
        return Redirect::to('pago/salario');
    }
}

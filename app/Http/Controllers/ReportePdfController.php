<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Http\Requests\ReportePdfFormRequest;

use DB;
use Fpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Response;
use Illuminate\Support\Collection;


class ReportePdfController extends Controller
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
           
            return view('reportes.pdf.create');
            //return Redirect::action('ReporteController@ventas',"2019-01-06");

        }
    }
    public function create(){
        return view('reportes.pdf.create');
    }
    public function store (ReportePdfFormRequest $request)
    {
        $fechaInicio=$request->get('fechaInicio');
        $fechaFin=$request->get('fechaFin');
        $tiporeporte=$request->get('tiporeporte');
        if($tiporeporte == 'ventas'){
            return Redirect::action('ReportePdfController@ventas',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin]);
        }
        if($tiporeporte == 'facturas'){
            return Redirect::action('ReportePdfController@facturaspdf',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin]);
        }
        if($tiporeporte == 'compras'){
            return Redirect::action('ReportePdfController@compraspdf',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin]);
        }
       
    }
    public function ventas($fechaInicio,$fechaFin){

             //obtener mes y año con carbon
            $fecha1 = Carbon::parse($fechaInicio);
            $fecha2 = Carbon::parse($fechaFin);
            //fecha actual
            $mytime = Carbon::now('America/Asuncion');
            $fechadereporte = $mytime->toDateTimeString();
            $fechadereporte = date_create($fechadereporte);
            $fechadereporte = date_format($fechadereporte, 'd/m/Y H:i:s');
            //fecha formateada
            $date1 = date_create($fechaInicio);
            $date2 = date_create($fechaFin);
            
            $fechaformateada1 = date_format($date1, 'd/m/Y');
            $fechaformateada2 = date_format($date2, 'd/m/Y');

            $clieMax=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->select('p.idpersona',DB::raw('SUM(v.total_venta) as total'))
            ->whereDate('v.fecha_hora','>=',$fecha1)
            ->whereDate('v.fecha_hora','<=',$fecha2)
            ->where('v.estado','=','ACTIVO')
            ->orderBy('total','desc')
            ->groupBy('p.idpersona')
            ->first();
            $clienteMax=DB::table('venta as v')
            ->join('persona as p','v.idcliente','=','p.idpersona')
            ->select('p.nombre','p.idpersona',DB::raw('SUM(v.total_venta) as total'))
            ->whereDate('v.fecha_hora','>=',$fecha1)
            ->whereDate('v.fecha_hora','<=',$fecha2)
            ->where('v.estado','=','ACTIVO')
            ->orderBy('total','desc')
            ->groupBy('p.nombre','p.idpersona')
            ->get();

            $cantMaxunidad=DB::table('venta as x')
                    ->join('detalle_venta as dvx','x.idventa','=','dvx.idventa')
                    ->join('articulo as ax','dvx.idarticulo','=','ax.idarticulo')
                    ->select('dvx.idarticulo',DB::raw('SUM(dvx.cantidad) as cantidad'))
                    ->whereDate('x.fecha_hora','>=',$fecha1)
                    ->whereDate('x.fecha_hora','<=',$fecha2)
                    ->where('x.estado','=','ACTIVO')
                    ->where('dvx.unidad_medida','=','unidad')
                    ->orderBy('cantidad','desc')
                    ->groupBy('dvx.idarticulo')
                    ->first();
            $artMaxunidad=DB::table('venta as v')
                    ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                    ->join('articulo as a','dv.idarticulo','=','a.idarticulo')
                    ->select('a.nombre','dv.idarticulo',DB::raw('SUM(dv.cantidad) as suma'))
                    ->whereDate('v.fecha_hora','>=',$fecha1)
                    ->whereDate('v.fecha_hora','<=',$fecha2)
                    ->where('v.estado','=','ACTIVO')
                    ->where('dv.unidad_medida','=','unidad')
                    ->orderBy('dv.cantidad','desc')
                    ->groupBy('a.nombre','dv.idarticulo')
                    ->get();
            $cantMaxservicio=DB::table('venta as x')
                    ->join('detalle_venta as dvx','x.idventa','=','dvx.idventa')
                    ->join('articulo as ax','dvx.idarticulo','=','ax.idarticulo')
                    ->select('dvx.idarticulo',DB::raw('SUM(dvx.cantidad) as cantidad'))
                    ->whereDate('x.fecha_hora','>=',$fecha1)
                    ->whereDate('x.fecha_hora','<=',$fecha2)
                    ->where('x.estado','=','ACTIVO')
                    ->where('dvx.unidad_medida','=','servicio')
                    ->orderBy('cantidad','desc')
                    ->groupBy('dvx.idarticulo')
                    ->first();
                   
            $artMaxservicio=DB::table('venta as v')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->join('articulo as a','dv.idarticulo','=','a.idarticulo')
            ->select('a.nombre','dv.idarticulo',DB::raw('SUM(dv.cantidad) as suma'))
            ->whereDate('v.fecha_hora','>=',$fecha1)
            ->whereDate('v.fecha_hora','<=',$fecha2)
            ->where('v.estado','=','ACTIVO')
            ->where('dv.unidad_medida','=','servicio')
            ->orderBy('dv.cantidad','desc')
            ->groupBy('a.nombre','dv.idarticulo')
            ->get();

            $cantMaxgramos=DB::table('venta as x')
            ->join('detalle_venta as dvx','x.idventa','=','dvx.idventa')
            ->join('articulo as ax','dvx.idarticulo','=','ax.idarticulo')
            ->select('dvx.idarticulo',DB::raw('SUM(dvx.cantidad) as cantidad'))
            ->whereDate('x.fecha_hora','>=',$fecha1)
            ->whereDate('x.fecha_hora','<=',$fecha2)
            ->where('x.estado','=','ACTIVO')
            ->where('dvx.unidad_medida','=','gramos')
            ->orderBy('cantidad','desc')
            ->groupBy('dvx.idarticulo')
            ->first();

            $artMaxgramos=DB::table('venta as v')
            ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
            ->join('articulo as a','dv.idarticulo','=','a.idarticulo')
            ->select('a.nombre','dv.idarticulo',DB::raw('SUM(dv.cantidad) as cantidad'))
            ->whereDate('v.fecha_hora','>=',$fecha1)
            ->whereDate('v.fecha_hora','<=',$fecha2)
            ->where('v.estado','=','ACTIVO')
            ->where('dv.unidad_medida','=','gramos')
            ->orderBy('dv.cantidad','desc')
            ->groupBy('a.nombre','dv.idarticulo')
            ->get();

          
                //Obtenemos los registros
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereDate('v.fecha_hora','>=',$fecha1)
                ->whereDate('v.fecha_hora','<=',$fecha2)
                ->where('v.estado','=','ACTIVO')
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereDate('v.fecha_hora','>=',$fecha1)
                ->whereDate('v.fecha_hora','<=',$fecha2)
                ->where('v.estado','=','ACTIVO')
                ->orderBy('v.idventa','asc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','A4');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,5,utf8_decode("Reporte de Ventas "),0,"","C");
         $pdf::Ln();
         $pdf::Cell(0,5,utf8_decode($fechadereporte),0,"","C");
         $pdf::Ln();
         $pdf::Cell(0,6,utf8_decode("Desde: ".$fechaformateada1),0,"","");
         $pdf::Ln();
         $pdf::Cell(5,6,utf8_decode("Hasta: ".$fechaformateada2),0,"","");
         $pdf::Ln();
            if($clienteMax){
                $pdf::Cell(5,7,utf8_decode("**Clientes con mayor compras**"),0,"","");
                $pdf::Ln();
                foreach($clienteMax as $cmax){
                    if($clieMax->total == $cmax->total){
                        $pdf::Cell(5,7,utf8_decode($cmax->nombre." ".number_format($cmax->total,0,'','.')." Gs."),0,"","");
                        $pdf::Ln();
                    }
                }
                
            }
            if($artMaxunidad){
                $pdf::Cell(5,7,utf8_decode("**Artículos más vendido por unidad**"),0,"","");
                $pdf::Ln();
                foreach($artMaxunidad as $artmaxu){
                   if($artmaxu->suma == $cantMaxunidad->cantidad){
                    $pdf::Cell(5,7,utf8_decode($artmaxu->nombre.", Cantidad: ".$artmaxu->suma." unidades"),0,"","");
                    $pdf::Ln();
                   }
                }
                
            }
            if($artMaxservicio){
                $pdf::Cell(5,7,utf8_decode("**Servicio más solicitado**"),0,"","");
                $pdf::Ln();
                foreach($artMaxservicio as $artmaxs){
                   if($artmaxs->suma == $cantMaxservicio->cantidad){
                    $pdf::Cell(5,7,utf8_decode($artmaxs->nombre.", Cantidad: ".$artmaxs->suma." servicios"),0,"","");
                    $pdf::Ln();
                   }
                }
                
            }
            if($artMaxgramos){
                $pdf::Cell(5,7,utf8_decode("**Artículos más vendido por kilo**"),0,"","");
                $pdf::Ln();
                foreach($artMaxgramos as $artmaxg){
                    if($artmaxg->cantidad == $cantMaxgramos->cantidad){
                        $pdf::Cell(5,7,utf8_decode($artmaxg->nombre.", Cantidad: ".($artmaxg->cantidad/1000)." Kg"),0,"","");
                        $pdf::Ln();
                    }
                }
                
                
            }
            
         foreach ($ventas as $ven)
         {
           $pdf::Cell(0,12,utf8_decode("Total de Ventas: ".number_format($ven->total,0,'','.')."Gs."),0,"","");
            }

         $pdf::Ln();
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190  
         $pdf::cell(15,8,utf8_decode("Venta"),1,"","C",true);      
         $pdf::cell(35,8,utf8_decode("Fecha - Hora"),1,"","C",true);
         $pdf::cell(80,8,utf8_decode("Cliente"),1,"","C",true);
         $pdf::cell(35,8,utf8_decode("Comprobante"),1,"","C",true);
         $pdf::cell(25,8,utf8_decode("Total"),1,"","C",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         //impirmir todas las ventas
         $cantidadVentas = 0; //contador de ventas en grilla
         foreach ($registros as $reg)
         {  
            $cantidadVentas = $cantidadVentas + 1;
            $pdf::cell(15,8,utf8_decode($cantidadVentas),1,"","C",true);
            //formtaear fecha de grilla
            $fechagrilla = $reg->fecha_hora;
            $fechagrilla = date_create($fechagrilla);
            $fechagrilla = date_format($fechagrilla, 'd/m/Y H:i:s');
            $pdf::cell(35,8,utf8_decode($fechagrilla),1,"","C",true);

            $pdf::cell(80,8,utf8_decode($reg->nombre),1,"","C",true);
            $pdf::cell(35,8,utf8_decode($reg->tipo_comprobante),1,"","C",true);
            $pdf::cell(25,8,number_format($reg->total_venta,0,'','.'),1,"","C",true);
            $pdf::Ln(); 
         }
         foreach ($ventas as $ven)
         {
            $pdf::cell(50,8,"Total de ventas Gs./ ".number_format($ven->total,0,'','.'),1,"","R",true);
            $pdf::Ln();
            }
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        //obener el id y cargar en una variable
        $usuarioActual = $user->id;
        $ruta = 'pdf/'.$usuarioActual.'_reporteventasmensual.pdf';
        $pdf::Ln();
        $pdf::Output($ruta, 'F');
        return view('reportes.pdf.vistapdf');
   }

   //REPORTE DE FACTURAS PDF
   public function facturaspdf($fechaInicio,$fechaFin){

    //obtener mes y año con carbon
   $fecha1 = Carbon::parse($fechaInicio);
   $fecha2 = Carbon::parse($fechaFin);
   //fecha actual
   $mytime = Carbon::now('America/Asuncion');
   $fechadereporte = $mytime->toDateTimeString();
   $fechadereporte = date_create($fechadereporte);
   $fechadereporte = date_format($fechadereporte, 'd/m/Y H:i:s');
   //fecha formateada
   $date1 = date_create($fechaInicio);
   $date2 = date_create($fechaFin);
   
   $fechaformateada1 = date_format($date1, 'd/m/Y');
   $fechaformateada2 = date_format($date2, 'd/m/Y');


 
       //Obtenemos los registros
       $ventas=DB::table('factura as f')
       ->select(DB::raw('sum(f.total_venta) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',1)
       ->groupBy()
       ->first();
       $registros=DB::table('factura as f')
       ->join('persona as p','f.idcliente','=','p.idpersona')
       ->select('p.nombre','p.num_documento','f.nro_factura','f.timbrado','f.exentas','f.impuesto5','f.impuesto10','f.total_venta','f.fecha_hora','f.tipo_documento')
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->orderBy('f.tipo_documento','f.fecha_hora','asc')
       ->groupBy()
       ->get();

       //subtotal exentas
       $exentas=DB::table('factura as f')
       ->select(DB::raw('sum(f.exentas) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',1)
       ->groupBy()
       ->first();
       //subtotal impuesto5
       $impuesto5=DB::table('factura as f')
       ->select(DB::raw('sum(f.impuesto5) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',1)
       ->groupBy()
       ->first();
       //subtotal impuesto10
       $impuesto10=DB::table('factura as f')
       ->select(DB::raw('sum(f.impuesto10) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',1)
       ->groupBy()
       ->first();


       //NOTA DE CREDITO
       $notacredito=DB::table('factura as f')
       ->select(DB::raw('sum(f.total_venta) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',3)
       ->groupBy()
       ->first();
       //subtotal exentas
       $exentasnotacredito=DB::table('factura as f')
       ->select(DB::raw('sum(f.exentas) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',3)
       ->groupBy()
       ->first();
       //subtotal impuesto5
       $impuesto5notacredito=DB::table('factura as f')
       ->select(DB::raw('sum(f.impuesto5) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',3)
       ->groupBy()
       ->first();
       //subtotal impuesto10
       $impuesto10notacredito=DB::table('factura as f')
       ->select(DB::raw('sum(f.impuesto10) as total'))
       ->whereDate('f.fecha_hora','>=',$fecha1)
       ->whereDate('f.fecha_hora','<=',$fecha2)
       ->where('f.estado','=','ACTIVO')
       ->where('f.tipo_documento','=',3)
       ->groupBy()
       ->first();

//Ponemos la hoja Horizontal (L)
$pdf = new FPDF();
$pdf::AddPage('L','A4');
$pdf::SetTextColor(35,56,113);
$pdf::SetFont('Arial','B',11);
$pdf::Cell(0,5,utf8_decode("Reporte de Facturas "),0,"","C");
$pdf::Ln();
$pdf::Cell(0,5,utf8_decode($fechadereporte),0,"","C");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("Desde: ".$fechaformateada1." "."Hasta: ".$fechaformateada2),0,"","C");
$pdf::Ln();
//calcular iva factura
$iva5factura = round($impuesto5->total/21, 0, PHP_ROUND_HALF_UP);
$iva10factura = round($impuesto10->total/11, 0, PHP_ROUND_HALF_UP);

//calcular iva nota credito
$iva5notacredito = round($impuesto5notacredito->total/21, 0, PHP_ROUND_HALF_UP);
$iva10facturanotacredito = round($impuesto10notacredito->total/11, 0, PHP_ROUND_HALF_UP);

//factura
$pdf::Cell(0,8,utf8_decode("FACTURA"),0,"","L");
$pdf::Cell(0,8,utf8_decode("NOTA DE CREDITO"),0,"","R");//NOTA CREDITO
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Exentas: ".number_format($exentas->total,0,'','.')." Gs."),0,"","L");
$pdf::Cell(0,8,utf8_decode("Exentas: ".number_format($exentasnotacredito->total,0,'','.')." Gs."),0,"","R");//NOTA CREDITO
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 5%: ".number_format($impuesto5->total,0,'','.')." Gs."),0,"","L");
$pdf::Cell(0,8,utf8_decode("Subtotal 5%: ".number_format($impuesto5notacredito->total,0,'','.')." Gs."),0,"","R");//NOTA CREDITO
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 5%: ".number_format($iva5factura,0,'','.')." Gs."),0,"","L");
$pdf::Cell(0,6,utf8_decode("IVA 5%: ".number_format($iva5notacredito,0,'','.')." Gs."),0,"","R"); //NOTA CREDITO
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 10%: ".number_format($impuesto10->total,0,'','.')." Gs."),0,"","L");
$pdf::Cell(0,8,utf8_decode("Subtotal 10%: ".number_format($impuesto10notacredito->total,0,'','.')." Gs."),0,"","R");//NOTA CREDITO
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 10%: ".number_format($iva10factura,0,'','.')." Gs."),0,"","L");
$pdf::Cell(0,6,utf8_decode("IVA 10%: ".number_format($iva10facturanotacredito,0,'','.')." Gs."),0,"","R");//NOTA CREDITO
$pdf::Ln();
  $pdf::Cell(0,12,utf8_decode("Subtotal Factura: ".number_format($ventas->total,0,'','.')." Gs."),0,"","L");
  $pdf::Cell(0,12,utf8_decode("Subtotal Nta. Cred.: ".number_format($notacredito->total,0,'','.')." Gs."),0,"","R");
  $pdf::Ln();

  //TOTAL FACTURA + NOTA DE CREDITO
$pdf::Cell(0,8,utf8_decode("FACTURA + NOTA DE CREDITO"),0,"","C");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Exentas: ".number_format($exentas->total + $exentasnotacredito->total,0,'','.')." Gs."),0,"","C");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 5%: ".number_format($impuesto5->total + $impuesto5notacredito->total,0,'','.')." Gs."),0,"","C");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 5%: ".number_format(($iva5factura) + ($iva5notacredito),0,'','.')." Gs."),0,"","C");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 10%: ".number_format($impuesto10->total + $impuesto10notacredito->total,0,'','.')." Gs."),0,"","C");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 10%: ".number_format(($iva10factura) + ($iva10facturanotacredito),0,'','.')." Gs."),0,"","C");
$pdf::Ln();

  $pdf::Cell(0,12,utf8_decode("TOTAL: ".number_format($ventas->total + $notacredito->total,0,'','.')." Gs."),0,"","C");
   

$pdf::Ln();

$pdf::Ln();
$pdf::SetTextColor(0,0,0);  // Establece el color del texto 
$pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
$pdf::SetFont('Arial','B',9); 
//El ancho de las columnas debe de sumar promedio 190  
$pdf::cell(8,8,utf8_decode("Cant."),1,"","C",true);   
$pdf::cell(25,8,utf8_decode("Nro Factura"),1,"","C",true);   
$pdf::cell(15,8,utf8_decode("Timbrado"),1,"","C",true);    
$pdf::cell(15,8,utf8_decode("Ruc"),1,"","C",true);
$pdf::cell(15,8,utf8_decode("Tipo"),1,"","C",true);  
$pdf::cell(20,8,utf8_decode("Fecha"),1,"","C",true);
$pdf::cell(70,8,utf8_decode("Nombre"),1,"","C",true);
$pdf::cell(15,8,utf8_decode("Exentas"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Imp. 5"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("IVA 5"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Imp. 10"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("IVA 10"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Total"),1,"","C",true);

$pdf::Ln();
$pdf::SetTextColor(0,0,0);  // Establece el color del texto 
$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
$pdf::SetFont("Arial","",7);
//impirmir todas las ventas
$cant_factura = 0;
foreach ($registros as $reg)
{  $cant_factura = $cant_factura + 1;
   $pdf::cell(8,8,utf8_decode($cant_factura),1,"","C",true);
   $pdf::cell(25,8,utf8_decode($reg->nro_factura),1,"","C",true);
   $pdf::cell(15,8,utf8_decode($reg->timbrado),1,"","C",true);
   $pdf::cell(15,8,utf8_decode($reg->num_documento),1,"","C",true);
   if($reg->tipo_documento == 1){
    $pdf::cell(15,8,utf8_decode('Factura'),1,"","C",true);
   }
   if($reg->tipo_documento == 3){
    $pdf::cell(15,8,utf8_decode('Nota Cred.'),1,"","C",true);
   }
   
   //formtaear fecha de grilla
   $fechagrilla = $reg->fecha_hora;
   $fechagrilla = date_create($fechagrilla);
   $fechagrilla = date_format($fechagrilla, 'd/m/Y');
   $pdf::cell(20,8,utf8_decode($fechagrilla),1,"","C",true);
   $pdf::cell(70,8,utf8_decode($reg->nombre),1,"","C",true);
   $pdf::cell(15,8,number_format($reg->exentas,0,'','.'),1,"","C",true);
    //calcular iva
    $iva5 = round($reg->impuesto5/21, 0, PHP_ROUND_HALF_UP);
    $iva10 = round($reg->impuesto10/11, 0, PHP_ROUND_HALF_UP);
   $pdf::cell(20,8,number_format($reg->impuesto5,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($iva5 ,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($reg->impuesto10,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($iva10 ,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($reg->total_venta,0,'','.'),1,"","C",true);
   $pdf::Ln(); 
}

   $pdf::cell(50,8,"TOTAL Gs./ ".number_format($ventas->total + $notacredito->total,0,'','.'),1,"","R",true);
   $pdf::Ln();
  
// Obtiene el objeto del Usuario Autenticado
$user = Auth::user();
//obener el id y cargar en una variable
$usuarioActual = $user->id;
$ruta = 'pdf/'.$usuarioActual.'_reporteventasmensual.pdf';
$pdf::Ln();
$pdf::Output($ruta, 'F');
return view('reportes.pdf.vistapdf');
}

//REPORTE DE COMPRAS PDF
public function compraspdf($fechaInicio,$fechaFin){

    //obtener mes y año con carbon
   $fecha1 = Carbon::parse($fechaInicio);
   $fecha2 = Carbon::parse($fechaFin);
   //fecha actual
   $mytime = Carbon::now('America/Asuncion');
   $fechadereporte = $mytime->toDateTimeString();
   $fechadereporte = date_create($fechadereporte);
   $fechadereporte = date_format($fechadereporte, 'd/m/Y H:i:s');
   //fecha formateada
   $date1 = date_create($fechaInicio);
   $date2 = date_create($fechaFin);
   
   $fechaformateada1 = date_format($date1, 'd/m/Y');
   $fechaformateada2 = date_format($date2, 'd/m/Y');

       //Obtenemos los registros
       $ingreso=DB::table('ingreso as i')
       ->select(DB::raw('sum(i.total_ingreso) as total'))
       ->whereDate('i.fecha_hora','>=',$fecha1)
       ->whereDate('i.fecha_hora','<=',$fecha2)
       ->where('i.estado','=','A')
       ->where('i.tipo_documento','=',1)
       ->groupBy()
       ->first();
       $registros=DB::table('ingreso as i')
       ->join('persona as p','i.idproveedor','=','p.idpersona')
       ->select('p.nombre','p.num_documento','i.num_comprobante','i.timbrado','i.exentas','i.impuesto5','i.impuesto10','i.total_ingreso','i.fecha_hora','i.tipo_documento')
       ->whereDate('i.fecha_hora','>=',$fecha1)
       ->whereDate('i.fecha_hora','<=',$fecha2)
       ->where('i.estado','=','A')
       ->orderBy('i.fecha_hora','asc')
       ->groupBy()
       ->get();

       //subtotal exentas
       $exentas=DB::table('ingreso as i')
       ->select(DB::raw('sum(i.exentas) as total'))
       ->whereDate('i.fecha_hora','>=',$fecha1)
       ->whereDate('i.fecha_hora','<=',$fecha2)
       ->where('i.estado','=','A')
       ->where('i.tipo_documento','=',1)
       ->groupBy()
       ->first();
       //subtotal impuesto5
       $impuesto5=DB::table('ingreso as i')
       ->select(DB::raw('sum(i.impuesto5) as total'))
       ->whereDate('i.fecha_hora','>=',$fecha1)
       ->whereDate('i.fecha_hora','<=',$fecha2)
       ->where('i.estado','=','A')
       ->where('i.tipo_documento','=',1)
       ->groupBy()
       ->first();
       //subtotal impuesto10
       $impuesto10=DB::table('ingreso as i')
       ->select(DB::raw('sum(i.impuesto10) as total'))
       ->whereDate('i.fecha_hora','>=',$fecha1)
       ->whereDate('i.fecha_hora','<=',$fecha2)
       ->where('i.estado','=','A')
       ->where('i.tipo_documento','=',1)
       ->groupBy()
       ->first();

//Ponemos la hoja Horizontal (L)
$pdf = new FPDF();
$pdf::AddPage('L','A4');
$pdf::SetTextColor(35,56,113);
$pdf::SetFont('Arial','B',11);
$pdf::Cell(0,5,utf8_decode("Reporte de Compras "),0,"","C");
$pdf::Ln();
$pdf::Cell(0,5,utf8_decode($fechadereporte),0,"","C");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("Desde: ".$fechaformateada1." "."Hasta: ".$fechaformateada2),0,"","C");
$pdf::Ln();
//calcular iva factura
$iva5factura = round($impuesto5->total/21, 0, PHP_ROUND_HALF_UP);
$iva10factura = round($impuesto10->total/11, 0, PHP_ROUND_HALF_UP);

//factura
$pdf::Cell(0,8,utf8_decode("FACTURA DE COMPRAS"),0,"","L");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Exentas: ".number_format($exentas->total,0,'','.')." Gs."),0,"","L");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 5%: ".number_format($impuesto5->total,0,'','.')." Gs."),0,"","L");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 5%: ".number_format($iva5factura,0,'','.')." Gs."),0,"","L");
$pdf::Ln();
$pdf::Cell(0,8,utf8_decode("Subtotal 10%: ".number_format($impuesto10->total,0,'','.')." Gs."),0,"","L");
$pdf::Ln();
$pdf::Cell(0,6,utf8_decode("IVA 10%: ".number_format($iva10factura,0,'','.')." Gs."),0,"","L");
$pdf::Ln();
  $pdf::Cell(0,12,utf8_decode("Subtotal Compras: ".number_format($ingreso->total,0,'','.')." Gs."),0,"","L");
  $pdf::Ln();
$pdf::Ln();
$pdf::SetTextColor(0,0,0);  // Establece el color del texto 
$pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
$pdf::SetFont('Arial','B',9); 
//El ancho de las columnas debe de sumar promedio 190  
$pdf::cell(8,8,utf8_decode("Cant."),1,"","C",true);   
$pdf::cell(25,8,utf8_decode("Nro Factura"),1,"","C",true);   
$pdf::cell(15,8,utf8_decode("Timbrado"),1,"","C",true);   
$pdf::cell(15,8,utf8_decode("Ruc"),1,"","C",true);   
$pdf::cell(15,8,utf8_decode("Tipo"),1,"","C",true);  
$pdf::cell(20,8,utf8_decode("Fecha"),1,"","C",true);
$pdf::cell(70,8,utf8_decode("Proveedor"),1,"","C",true);
$pdf::cell(15,8,utf8_decode("Exentas"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Imp. 5"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("IVA 5"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Imp. 10"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("IVA 10"),1,"","C",true);
$pdf::cell(20,8,utf8_decode("Total"),1,"","C",true);

$pdf::Ln();
$pdf::SetTextColor(0,0,0);  // Establece el color del texto 
$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
$pdf::SetFont("Arial","",7);
//impirmir todas las ventas
$cant_factura = 0;
foreach ($registros as $reg)
{  $cant_factura = $cant_factura + 1;
   $pdf::cell(8,8,utf8_decode($cant_factura),1,"","C",true);
   $pdf::cell(25,8,utf8_decode($reg->num_comprobante),1,"","C",true);
   $pdf::cell(15,8,utf8_decode($reg->timbrado),1,"","C",true);
   $pdf::cell(15,8,utf8_decode($reg->num_documento),1,"","C",true);
   if($reg->tipo_documento == 1){
    $pdf::cell(15,8,utf8_decode('Factura'),1,"","C",true);
   }
   if($reg->tipo_documento == 3){
    $pdf::cell(15,8,utf8_decode('Nota Cred.'),1,"","C",true);
   }
   
   //formtaear fecha de grilla
   $fechagrilla = $reg->fecha_hora;
   $fechagrilla = date_create($fechagrilla);
   $fechagrilla = date_format($fechagrilla, 'd/m/Y');
   $pdf::cell(20,8,utf8_decode($fechagrilla),1,"","C",true);
   $pdf::cell(70,8,utf8_decode($reg->nombre),1,"","C",true);
   $pdf::cell(15,8,number_format($reg->exentas,0,'','.'),1,"","C",true);
    //calcular iva
    $iva5 = round($reg->impuesto5/21, 0, PHP_ROUND_HALF_UP);
    $iva10 = round($reg->impuesto10/11, 0, PHP_ROUND_HALF_UP);
   $pdf::cell(20,8,number_format($reg->impuesto5,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($iva5 ,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($reg->impuesto10,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($iva10 ,0,'','.'),1,"","C",true);
   $pdf::cell(20,8,number_format($reg->total_ingreso,0,'','.'),1,"","C",true);
   $pdf::Ln(); 
}

   $pdf::cell(50,8,"TOTAL Gs./ ".number_format($ingreso->total,0,'','.'),1,"","R",true);
   $pdf::Ln();
  
// Obtiene el objeto del Usuario Autenticado
$user = Auth::user();
//obener el id y cargar en una variable
$usuarioActual = $user->id;
$ruta = 'pdf/'.$usuarioActual.'_reporteventasmensual.pdf';
$pdf::Ln();
$pdf::Output($ruta, 'F');
return view('reportes.pdf.vistapdf');
}
}

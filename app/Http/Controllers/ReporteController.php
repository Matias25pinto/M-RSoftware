<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Http\Requests\ReporteFormRequest;

use DB;
use Fpdf;
use Carbon\Carbon;
use Excel;

use Response;
use Illuminate\Support\Collection;

class ReporteController extends Controller
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
           
            return view('reportes.venta.index');
            //return Redirect::action('ReporteController@ventas',"2019-01-06");

        }
    }
    public function create(){
        return view('reportes.venta.create');
    }
    public function store (ReporteFormRequest $request)
    {
       
        $fechaInicio=$request->get('fechaInicio');
        $fechaFin=$request->get('fechaFin');
        $formato=$request->get('formato');
        $reporte=$request->get('reporte');
       
        if($reporte == 'ventas'){
            return Redirect::action('ReporteController@hechaukaventas',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'formato'=>$formato]);
        } 
        if($reporte == 'compras'){
            return Redirect::action('ReporteController@hechaukacompras',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'formato'=>$formato]);
        }
        if($reporte == 'resumen'){
            return Redirect::action('ReporteController@resumen',['fechaInicio'=>$fechaInicio,'fechaFin'=>$fechaFin,'formato'=>$formato]);
        }

        
    }
    public function ventas($fechaInicio,$fechaFin,$formato){

        if ($fechaInicio == $fechaFin) {
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereDate('v.fecha_hora', '=', $fechaInicio)
                ->where('v.estado','=','ACTIVO')
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
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFinMasUno))
                ->where('v.estado','=','ACTIVO')
                ->orderBy('v.idventa','desc')
                ->groupBy('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado')
                ->get();
            }
            else{
                $ventas=DB::table('venta as v')
                ->select(DB::raw('sum(v.total_venta) as total'))
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
                ->groupBy()
                ->get();
                $registros=DB::table('venta as v')
                ->join('persona as p','v.idcliente','=','p.idpersona')
                ->join('detalle_venta as dv','v.idventa','=','dv.idventa')
                ->select('v.idventa','v.fecha_hora','p.nombre','v.tipo_comprobante','v.serie_comprobante','v.num_comprobante','v.estado','v.total_venta')
                ->whereBetween('v.fecha_hora', array($fechaInicio, $fechaFin))
                ->where('v.estado','=','ACTIVO')
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
         
         foreach ($ventas as $ven)
         {
           $pdf::Cell(0,10,utf8_decode("Total de Ventas: ".number_format($ven->total,0,'','.')."Gs."),0,"","C");
            }
        

         $pdf::Ln();
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190  
         $pdf::cell(15,8,utf8_decode("Venta"),1,"","L",true);      
         $pdf::cell(35,8,utf8_decode("Fecha   -   Hora"),1,"","L",true);
         $pdf::cell(70,8,utf8_decode("Cliente"),1,"","L",true);
         $pdf::cell(45,8,utf8_decode("Comprobante"),1,"","L",true);
         $pdf::cell(25,8,utf8_decode("Total"),1,"","R",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(15,8,utf8_decode($reg->idventa),1,"","L",true);
            $pdf::cell(35,8,utf8_decode($reg->fecha_hora),1,"","L",true);
            $pdf::cell(70,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(45,8,utf8_decode($reg->tipo_comprobante),1,"","L",true);
            $pdf::cell(25,8,number_format($reg->total_venta,0,'','.'),1,"","R",true);
            $pdf::Ln(); 
         }
         foreach ($ventas as $ven)
         {
            $pdf::cell(50,8,"Total de ventas Gs./ ".number_format($ven->total,0,'','.'),1,"","R",true);
            $pdf::Ln();
            }
         $pdf::Output('ReporteDeVentass.pdf','I'); 

         exit;
   }

   //Generar ventas hechauka

   public function hechaukaventas($fechaInicio,$fechaFin,$formato){       
        Excel::create('ventas hechauka'.$fechaInicio.'-'.$fechaFin,function($excel) use($fechaInicio,$fechaFin){
        $excel->sheet('Datos',function($sheet) use($fechaInicio, $fechaFin){


        //header
        //obtener mes y año con carbon
        $fecha1 = Carbon::parse($fechaInicio);
        $mfecha1 = str_pad($fecha1->month, 2, "0", STR_PAD_LEFT);
        $afecha1 = $fecha1->year;

        $fecha2 = Carbon::parse($fechaFin);
        $mfecha2 = str_pad($fecha2->month, 2, "0", STR_PAD_LEFT);
        $afecha2 = $fecha2->year;

        if($mfecha1 == $mfecha2 and $afecha1 == $afecha2){
            $fecha = $afecha1.$mfecha1;
        }

        //query de los datos de factura
        $facturas = DB::table('factura as f')
        ->join('persona as p','f.idcliente','=','p.idpersona')
        ->select('p.num_documento','p.nombre','f.nro_factura','f.fecha_hora','f.impuesto10','f.impuesto5','f.exentas','f.total_venta','f.timbrado','f.tipo_documento','f.condicion_venta','f.cuotas')
        ->where('f.estado','=','ACTIVO')
        ->whereBetween('f.fecha_hora', array($fechaInicio, $fechaFin))
        ->orderBy('f.nro_factura','ASC')
        ->get();

        $data = [];
     
        $sumaTotal = 0;
        //CONTADOR DE REGISTROS
        $cantidad_registros = 0;
        

        //variables ocasionales 
        $base10_ocasional =  0;
        $base5_ocasional = 0;
        $exentas_ocasional =  0;
        $iva5_ocasional = 0;
        $iva10_ocasional = 0;
        $subtotal_ocasional = 0;

        foreach($facturas as $factura){
            if($factura->num_documento != '44444401-7'){
                $row = [];
                //obtener el div con php, explode(separador, $variable)
                $rucdividido = explode("-", $factura->num_documento);
                //calcular impuestos
                $impuesto10 = $factura->impuesto10;
                $impuesto5 = $factura->impuesto5;
                $exentas = $factura->exentas;
        
                //calcular iva
                $iva5 = round($factura->impuesto5/21, 0, PHP_ROUND_HALF_UP);
                $iva10 = round($factura->impuesto10/11, 0, PHP_ROUND_HALF_UP);
                //calcular la base es el subtotal - el iva
                $base5 = $impuesto5 - $iva5;
                $base10 = $impuesto10 -  $iva10;
        
                $row[0] = 2;
                $row[1]= $rucdividido[0];
                if($rucdividido[1]>0){
                    $row[2]= $rucdividido[1];
                }else{
                    $row[2]='0';
                }
                $row[3]= $factura->nombre;
                //tipo de documento
                $row[4]= (int)$factura->tipo_documento;;
                $row[5]= $factura->nro_factura;
                //convertir fecha
                $newDate = date("d/m/Y", strtotime($factura->fecha_hora));
                $row[6]= $newDate;
                if($base10>0){
                    $row[7] = $base10;
                }else{
                    $row[7] = '0';
                }
                if($iva10>0){
                    $row[8]= (int)$iva10;
                }else{
                    $row[8] = '0';
                }
                if($base5>0){
                    $row[9]= (int)$base5;
                }else{
                    $row[9] = '0';
                }
                if($iva5>0){
                    $row[10]= (int)$iva5;
                }else{
                    $row[10] = '0';
                }
                if($exentas>0){
                    $row[11]= (int)$exentas;
                }else{
                    $row[11] = '0';
                }
                $row[12]= (int)$base10 + (int)$iva10 + (int)$base5 + (int)$iva5 + (int)$exentas;
                $row[13]= (int)$factura->condicion_venta;
                if((int)$factura->condicion_venta == 1){
                    $row[14] = '0';
                }else{
                    $row[14] = (int)$factura->cuotas;
                }
                $row[15]= (int)$factura->timbrado;
        
                $data[] = $row;
                $sumaTotal = $sumaTotal + $base10 + $iva10 + $base5 + $iva5 + $exentas;
                $cantidad_registros = $cantidad_registros + 1;
            }
            if($factura->num_documento == '44444401-7'){

                //calcular impuestos
                $impuesto10 = $factura->impuesto10;
                $impuesto5 = $factura->impuesto5;
                $exentas = $factura->exentas;
        
                //calcular iva
                $iva5 = round($factura->impuesto5/21, 0, PHP_ROUND_HALF_UP);
                $iva10 = round($factura->impuesto10/11, 0, PHP_ROUND_HALF_UP);
                //calcular la base es el subtotal - el iva
                $base5 = $impuesto5 - $iva5;
                $base10 = $impuesto10 -  $iva10;

                $base10_ocasional =  $base10_ocasional + $base10;
                $base5_ocasional = $base5_ocasional + $base5;
                $exentas_ocasional =  $exentas_ocasional + $exentas;
                $iva5_ocasional = $iva5_ocasional +  $iva5;
                $iva10_ocasional = $iva10_ocasional + $iva10;
                $subtotal_ocasional = $subtotal_ocasional + (int)$base10 + (int)$iva10 + (int)$base5 + (int)$iva5 + (int)$exentas;    
            }  
        } 
        if($subtotal_ocasional > 0){

            $row[0] = 2;
            $row[1]= '44444401';
            $row[2]= 7;
            $row[3]= 'CLIENTE OCASIONAL';
            //tipo de documento
            $row[4]= '0';
            $row[5]= '0';
           //se carga fecha inicio al cliente ocasional
            $row[6]= $fechaInicio;
            if($base10_ocasional>0){
                $row[7] = $base10_ocasional;
            }else{
                $row[7] = '0';
            }
            if($iva10_ocasional>0){
                $row[8]= (int)$iva10_ocasional;
            }else{
                $row[8] = '0';
            }
            if($base5_ocasional>0){
                $row[9]= (int)$base5_ocasional;
            }else{
                $row[9] = '0';
            }
            if($iva5_ocasional>0){
                $row[10]= (int)$iva5_ocasional;
            }else{
                $row[10] = '0';
            }
            if($exentas_ocasional>0){
                $row[11]= (int)$exentas_ocasional;
            }else{
                $row[11] = '0';
            }
             $row[12]= $base10_ocasional + $iva10_ocasional +  $base5_ocasional + $iva5_ocasional + $exentas_ocasional;
             $row[13]= 1;
            
             $row[14] = '0';
            
            $row[15]= '0';
        
            $data[] = $row;
            $cantidad_registros = $cantidad_registros + 1;
            $sumaTotal = $sumaTotal + $base10_ocasional + $iva10_ocasional +  $base5_ocasional + $iva5_ocasional + $exentas_ocasional;
        
    }
    $sheet->fromArray($data);
    //encabezado 
    $sheet->row(1,['1',$fecha,'1','921','221','3518483','3','COMERCIAL FANE','0','0','0',$cantidad_registros,$sumaTotal,'2',' ',' ']);
    
    });
})->export($formato);

    exit;
}



   //Generar compras hechauka

   public function hechaukacompras($fechaInicio,$fechaFin,$formato){

       
    Excel::create('compras hechauka'.$fechaInicio.'-'.$fechaFin,function($excel) use($fechaInicio,$fechaFin){
    $excel->sheet('Datos',function($sheet) use($fechaInicio, $fechaFin){


    //obtener mes y año con carbon
    $fecha1 = Carbon::parse($fechaInicio);
    $mfecha1 = str_pad($fecha1->month, 2, "0", STR_PAD_LEFT);
    $afecha1 = $fecha1->year;

    $fecha2 = Carbon::parse($fechaFin);
    $mfecha2 = str_pad($fecha2->month, 2, "0", STR_PAD_LEFT);
    $afecha2 = $fecha2->year;

    if($mfecha1 == $mfecha2 and $afecha1 == $afecha2){
       $fecha = $afecha1.$mfecha1;
    }

    //query de los datos de factura
    $facturas = DB::table('ingreso as i')
    ->join('persona as p','i.idproveedor','=','p.idpersona')
    ->select('p.num_documento','p.nombre','i.num_comprobante','i.fecha_hora','i.impuesto10','i.impuesto5','i.exentas','i.total_ingreso','i.timbrado','i.tipo_documento','i.condicion_compra','i.cuotas','i.tipo_operacion')
    ->where('i.estado','=','A')
    ->whereBetween('i.fecha_hora', array($fechaInicio, $fechaFin))
    ->orderBy('i.fecha_hora','ASC')
    ->get();
    
    $data = [];
     
    $sumaTotal = 0;
    //CONTADOR DE REGISTROS
        $cantidad_registros = 0;
        

    //variables ocasionales 
    $base10_ocasional =  0;
    $base5_ocasional = 0;
    $exentas_ocasional =  0;
    $iva5_ocasional = 0;
    $iva10_ocasional = 0;
    $subtotal_ocasional = 0;

    foreach($facturas as $factura){
        if($factura->num_documento != '44444401-7'){
            $row = [];
            //obtener el div con php, explode(separador, $variable)
            $rucdividido = explode("-", $factura->num_documento);
            //calcular impuestos
            $impuesto10 = $factura->impuesto10;
            $impuesto5 = $factura->impuesto5;
            $exentas = $factura->exentas;
        
            //calcular iva
            $iva5 = round($factura->impuesto5/21, 0, PHP_ROUND_HALF_UP);
            $iva10 = round($factura->impuesto10/11, 0, PHP_ROUND_HALF_UP);
            //calcular la base es el subtotal - el iva
            $base5 = $impuesto5 - $iva5;
            $base10 = $impuesto10 -  $iva10;
        
            $row[0] = 2;
            $row[1]= $rucdividido[0];
            $row[2]= $rucdividido[1];
            $row[3]= $factura->nombre;
            //tipo de documento
            $row[4]= $factura->timbrado;
            //tipo documento
            $row[5]= $factura->tipo_documento;
            $row[6]=$factura->num_comprobante;
            //convertir fecha
            $newDate = date("d/m/Y", strtotime($factura->fecha_hora));
            $row[7]= $newDate;
            if($base10>0){
                $row[8] = $base10;
            }else{
                $row[8] = '0';
            }
            if($iva10>0){
                $row[9]= $iva10;
            }else{
                $row[9] = '0';
            }
            if($base5>0){
                $row[10]= $base5;
            }else{
                $row[10] = '0';
            }
            if($iva5>0){
                $row[11]= $iva5;
            }else{
                $row[11] = '0';
            }
            if($exentas>0){
                $row[12]= $exentas;
            }else{
                $row[12] = '0';
            }
            
             $row[13]= '0';
             $row[14]= $factura->condicion_compra;
            if($factura->condicion_compra == 1){
                $row[15] = '0';
            }else{
                $row[15] = $factura->cuotas;
            }
        
            $data[] = $row;
            $sumaTotal = $sumaTotal + $base10 + $iva10 + $base5 + $iva5 + $exentas;
            $cantidad_registros = $cantidad_registros + 1;
        }
       
    } 
   
    $sheet->fromArray($data);
    //encabezado 
    $sheet->row(1,['1',$fecha,'1','911','211','3518483','3','COMERCIAL FANE','0','0','0',$cantidad_registros,$sumaTotal,'NO','2',' ']);
    
    });
})->export($formato);

    exit;
}
 //Resumen General mensual

 public function resumen($fechaInicio,$fechaFin,$formato){

    //crear el excel
    Excel::create('resumen mensual'.$fechaInicio.'-'.$fechaFin,function($excel) use($fechaInicio,$fechaFin){
    
    $excel->sheet('Datos',function($sheet) use($fechaInicio, $fechaFin){
        
        

    //obtener mes y año con carbon
    $fecha1 = Carbon::parse($fechaInicio);
    $mfecha1 = str_pad($fecha1->month, 2, "0", STR_PAD_LEFT);
    $afecha1 = $fecha1->year;

    $fecha2 = Carbon::parse($fechaFin);
    $mfecha2 = str_pad($fecha2->month, 2, "0", STR_PAD_LEFT);
    $afecha2 = $fecha2->year;

    if($mfecha1 == $mfecha2 and $afecha1 == $afecha2){
       $fecha = $afecha1.$mfecha1;
    }

    //query de los datos de las compras
    $compras = DB::table('ingreso as i')
    ->select(DB::raw('SUM(i.impuesto10) as impuesto10, SUM(i.impuesto5) as impuesto5, SUM(i.exentas) as exentas'))
    ->where('i.estado','=','A')
    ->whereBetween('i.fecha_hora', array($fechaInicio, $fechaFin))
    ->orderBy('i.fecha_hora','ASC')
    ->groupBy()
    ->get();

     //query de los datos de ventas
     $ventas = DB::table('factura as f')
     ->select(DB::raw('SUM(f.impuesto10) as impuesto10, SUM(f.impuesto5) as impuesto5, SUM(f.exentas) as exentas, f.tipo_documento'))
     ->where('f.estado','=','ACTIVO')
     ->whereBetween('f.fecha_hora', array($fechaInicio, $fechaFin))
     ->orderBy('f.nro_factura','ASC')
     ->groupBy('f.tipo_documento')
     ->get();
 
    //obtener mes y año con carbon
    $fecha1 = Carbon::parse($fechaInicio);
    $mfecha1 = str_pad($fecha1->month, 2, "0", STR_PAD_LEFT);
    $afecha1 = $fecha1->year;

    $fecha2 = Carbon::parse($fechaFin);
    $mfecha2 = str_pad($fecha2->month, 2, "0", STR_PAD_LEFT);
    $afecha2 = $fecha2->year;

    if($mfecha1 == $mfecha2 and $afecha1 == $afecha2){
       $fecha = $afecha1.$mfecha1;
    }
       
     $sheet->mergeCells('A1:G1');
     $sheet->row(1,['RESUMEN MES DE '.$fecha]);
     $sheet->cells('A1', function($cells) {

        // manipulate the range of cells
        $cells->setValignment('center');
    
    });
     
     $row = ['DESCRIPCIÓN','EFECTIVO','BASE 10 %','IVA 10% NETO','BASE 5 %','IVA 5 % NETO','EXENTAS'];
     $data[] = $row;
    foreach($ventas as $venta){
        if($venta->tipo_documento == 1){
            $row = [];
            $impuesto5_ventas = $venta->impuesto5;
            $impuesto10_ventas = $venta->impuesto10;
            $exentas_ventas = $venta->exentas;
        
            //calcular iva
            $iva5_ventas = round($impuesto5_ventas/21, 0, PHP_ROUND_HALF_UP);
            $iva10_ventas = round($impuesto10_ventas/11, 0, PHP_ROUND_HALF_UP);

            //calcular la base es el subtotal - el iva
            $base5_ventas = $impuesto5_ventas - $iva5_ventas;
            $base10_ventas = $impuesto10_ventas -  $iva10_ventas;
        
            $row[0] = 'VENTAS';
            $row[1]= $base10_ventas + $iva10_ventas + $base5_ventas + $iva5_ventas + $exentas_ventas;
            $row[2]=  $base10_ventas;
            $row[3]= $iva10_ventas;
         
            $row[4]= $base5_ventas;
           
            $row[5]= $iva5_ventas ;
            $row[6]= $exentas_ventas;
            
        
            $data[] = $row;
        //calcular nota de credito compras
        }elseif($venta->tipo_documento == 3){
            $row = [];
            $impuesto5_ventas = $venta->impuesto5;
            $impuesto10_ventas = $venta->impuesto10;
            $exentas_ventas = $venta->exentas;
        
            //calcular iva
            $iva5_ventas = round($impuesto5_ventas/21, 0, PHP_ROUND_HALF_UP);
            $iva10_ventas = round($impuesto10_ventas/11, 0, PHP_ROUND_HALF_UP);

            //calcular la base es el subtotal - el iva
            $base5_ventas = $impuesto5_ventas - $iva5_ventas;
            $base10_ventas = $impuesto10_ventas -  $iva10_ventas;
        
            $row[0] = 'NOTA CREDITO COMPRAS';
            $row[1]= $base10_ventas + $iva10_ventas + $base5_ventas + $iva5_ventas + $exentas_ventas;
            $row[2]=  $base10_ventas;
            $row[3]= $iva10_ventas;
         
            $row[4]= $base5_ventas;
           
            $row[5]= $iva5_ventas ;
            $row[6]= $exentas_ventas;
            
        
            $data[] = $row;
        }
    }
        foreach($compras as $compra){
        
            $row = [];
            
            
            $impuesto5_compra = $compra->impuesto5;
            $impuesto10_compra = $compra->impuesto10;
            $exentas_compra = $compra->exentas;
        
            //calcular iva
            $iva5_compra = round($impuesto5_compra/21, 0, PHP_ROUND_HALF_UP);
            $iva10_compra = round($impuesto10_compra/11, 0, PHP_ROUND_HALF_UP);

            //calcular la base es el subtotal - el iva
            $base5_compra = $impuesto5_compra - $iva5_compra;
            $base10_compra = $impuesto10_compra -  $iva10_compra;
        
            $row[0] = 'COMPRAS';
            $row[1]= $base10_compra + $iva10_compra + $base5_compra + $iva5_compra + $exentas_compra;
            $row[2]=  $base10_compra;
            $row[3]= $iva10_compra;
         
            $row[4]= $base5_compra;
           
            $row[5]= $iva5_compra ;
            $row[6]= $exentas_compra;
            
        
            $data[] = $row;
           
        }
   
            $sheet->fromArray($data);
            //encabezado 
            
    
        });
        })->export($formato);

            exit;
        }
        
}

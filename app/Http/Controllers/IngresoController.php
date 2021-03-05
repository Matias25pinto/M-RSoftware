<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Http\Requests\IngresoFormRequest;
use sisMR\Ingreso;
use sisMR\DetalleIngreso;
use DB;
use Illuminate\Support\Facades\Auth;
use Fpdf;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth',
            'verificarPermiso:administrador,deposito,contador'
            ]);
    }
    public function index(Request $request)
    {
        if ($request)
        {
           $query=trim($request->get('searchText'));
           $ingresos=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->join('articulo as art','art.idarticulo','=','di.idarticulo')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.estado')
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy('i.idingreso','desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.estado')
            ->paginate(10);
            return view('compras.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]);

        }
    }
    public function create()
    {
    	$personas=DB::table('persona as per')
        ->select('per.idpersona','per.nombre', 'per.num_documento','per.direccion','per.telefono')
        ->where('tipo_persona','=','Proveedor')
        ->get();
    	$articulos = DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo, " ",art.nombre) AS articulo'),'art.idarticulo', 'art.impuesto')
            ->where('art.estado','=','Activo')
            ->get();
        return view("compras.ingreso.create",["personas"=>$personas,"articulos"=>$articulos]);
    }

     public function store (IngresoFormRequest $request)
    {
        try{
        	$ingreso=new Ingreso;
	        $ingreso->idproveedor=$request->get('idproveedor');
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->timbrado=$request->get('timbrado');
            $ingreso->num_comprobante=$request->get('num_comprobante');
	        $ingreso->fecha_hora=$request->get('fechaInicio');        

              
            $ingreso->cuotas=$request->get('cuotas');       
            $ingreso->estado='A';
            $cont1 = 0;
            $iva_descuento = $request->get('iva_descuento');
            $total_descuento = $request->get('descuento_total');
            $idarticulo = $request->get('idarticulo');
            //descuento por articulo
            $descuento = $request->get('descuento');
            $cantidad = $request->get('cantidad');
            if( $total_descuento == 0 and $iva_descuento === 'ninguna'){
	            while($cont1 < count($idarticulo)){
                    $total_descuento = (int)$total_descuento + (int)($descuento[$cont1]*$cantidad[$cont1]);
                    $cont1=$cont1+1; 
                }
                $ingreso->descuento = (int)$total_descuento;
                $ingreso->total_ingreso = $request->get('total_ingreso');
                $ingreso->exentas = $request->get('exentas'); 
                $ingreso->impuesto5 = $request->get('impuesto5'); 
                $ingreso->impuesto10 = $request->get('impuesto10');
            }else{
                $tota_ingresoaux= $request->get('total_ingreso');
                $exentas_aux = $request->get('exentas'); 
                $impuesto5_aux = $request->get('impuesto5'); 
                $impuesto10_aux = $request->get('impuesto10');
                if($iva_descuento == 0){
                    $ingreso->total_ingreso = (int)$tota_ingresoaux - (int)$total_descuento;
                    $ingreso->exentas = (int)$exentas_aux - (int)$total_descuento;
                    
                    $ingreso->impuesto5 = $request->get('impuesto5'); 
                    $ingreso->impuesto10 = $request->get('impuesto10');
                }
                if($iva_descuento == 5){
                    $ingreso->total_ingreso = (int)$tota_ingresoaux - (int)$total_descuento;
                    $ingreso->impuesto5 = (int)$impuesto5_aux - (int)$total_descuento;
                    $ingreso->exentas = $request->get('exentas'); 
                    
                    $ingreso->impuesto10 = $request->get('impuesto10');
                }
                if($iva_descuento == 10){
                    $ingreso->total_ingreso = (int)$tota_ingresoaux - (int)$total_descuento;
                    $ingreso->impuesto10 =  (int)$impuesto10_aux - (int)$total_descuento;
                    $ingreso->exentas = $request->get('exentas'); 
                    $ingreso->impuesto5 = $request->get('impuesto5'); 
                }
                    $ingreso->descuento = $total_descuento;

            }
            
	        $ingreso->save();

            $bonificacion = $request->get('bonificacion');
            $precio_compra = $request->get('precio_compra');
            $impuesto = $request->get('impuesto');

	        $cont = 0;

	        while($cont < count($idarticulo)){
	            $detalle = new DetalleIngreso();
	            $detalle->idingreso= $ingreso->idingreso; 
                $detalle->idarticulo= $idarticulo[$cont];
                //cantidad es la suma de cantidad en la factura y la binificacion
	            $detalle->cantidad= $cantidad[$cont] + $bonificacion[$cont];
                $detalle->precio_compra= $precio_compra[$cont];
                $detalle->descuento= $descuento[$cont];
                $detalle->impuesto= $impuesto[$cont];
                $detalle->bonificacion=$bonificacion[$cont];

	            $detalle->save();
	            $cont=$cont+1;            
	        }

        	
            
        
            
            DB::commit();
           

        }catch(\Exception $e)
        {
            DB::rollback();
            return Redirect::action('IngresoController@index',"");
        }
        $id = $ingreso->idingreso;

        return Redirect::action('IngresoController@show',$id);
        
    }

    public function show($id)
    {
    	$ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.estado','i.descuento','p.num_documento','p.direccion','p.telefono','i.tipo_comprobante','i.timbrado',
                    'i.num_comprobante','i.exentas','i.impuesto5','i.impuesto10','i.total_ingreso','i.cuotas')
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.bonificacion','d.precio_compra','d.descuento','d.impuesto')
             ->where('d.idingreso','=',$id)
             ->orderby('d.impuesto','ASC')
             ->get();
        return view("compras.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    public function destroy($id)
    {
         $condicion=Ingreso::findOrFail($id);
        if ($condicion->estado == 'A') {
    	   $ingreso=Ingreso::findOrFail($id);
            $ingreso->Estado='C';
            $ingreso->update();
        }
        return Redirect::to('compras/ingreso');
    }
    public function reportec($id){
         //Obtengo los datos
        
    $ingreso=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','p.direccion','p.num_documento','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.idingreso','=',$id)
            ->first();

        $detalles=DB::table('detalle_ingreso as d')
             ->join('articulo as a','d.idarticulo','=','a.idarticulo')
             ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
             ->where('d.idingreso','=',$id)
             ->get();


        $pdf = new Fpdf();
        $pdf::AddPage();
       //TITULO DE REPORTE DE INTRESO
        $pdf::SetFont('Arial','B',15);//TIPO Y TAMAÑO DE LETRA
        $pdf::SetXY(80,20);//UBICACION EN LA HOJA
        $pdf::Cell(0,0,"REPORTE DE INGRESO");//IMPRIME EL TEXTO


        $pdf::SetFont('Arial','B',10);
        $pdf::SetXY(35,60);
        $pdf::Cell(0,0,"Nombre: ".utf8_decode($ingreso->nombre));
        $pdf::SetXY(35,69);
        $pdf::Cell(0,0,utf8_decode("Dirección: ".$ingreso->direccion));
        //***Parte de la derecha
        $pdf::SetXY(170,60);
        $pdf::Cell(0,0,utf8_decode("RUC: ".$ingreso->num_documento));
        $pdf::SetXY(170,69);
        $pdf::Cell(0,0,"Fecha: ".substr($ingreso->fecha_hora,0,10));
        $total=0;

        //Mostramos los detalles
            $pdf::SetXY(20,80);
            $pdf::MultiCell(30,0,"Cantidad");
            $pdf::SetXY(40,80);
            $pdf::MultiCell(120,0,"Articulo");
        $y=89;
        foreach($detalles as $det){
            $pdf::SetXY(20,$y);
            $pdf::MultiCell(10,0,"      ".$det->cantidad);
            $pdf::SetXY(40,$y);
            $pdf::MultiCell(120,0,utf8_decode($det->articulo));
            $y=$y+7;
        }


        $pdf::Output();
        exit;
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('ingreso as i')
            ->join('persona as p','i.idproveedor','=','p.idpersona')
            ->select('p.nombre','p.num_documento','i.timbrado','i.num_comprobante','i.fecha_hora','i.exentas','i.impuesto5','i.impuesto10','i.total_ingreso','i.descuento','i.cuotas','i.estado')
            ->where('i.estado','=','A')
            ->orderBy('i.fecha_hora','ASC')
            ->get();

         //Ponemos la hoja Horizontal (L)
         $pdf = new Fpdf('L','mm','Legal');
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado Compras"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',8); 
         //El ancho de las columnas debe de sumar promedio 190 
         $pdf::cell(5,8,utf8_decode("N°"),1,"","L",true);
         $pdf::cell(20,8,utf8_decode("Fecha"),1,"","L",true);
         $pdf::cell(38,8,utf8_decode("Proveedor"),1,"","L",true);
         $pdf::cell(15,8,utf8_decode("Nro RUC"),1,"","L",true);
         $pdf::cell(15,8,utf8_decode("Timbrado"),1,"","L",true);
         $pdf::cell(20,8,utf8_decode("Nro Factura"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Exe"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Imp 5%"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Imp 10%"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Des"),1,"","R",true);
         $pdf::cell(15,8,utf8_decode("Total"),1,"","R",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",7);
         $contador = 0;
         foreach ($registros as $reg)
         {
            $contador = $contador + 1;
            $pdf::cell(5,8,utf8_decode($contador),1,"","L",true);
            //convertir fecha
            $newDate = date("d/m/Y", strtotime($reg->fecha_hora));
            $pdf::cell(20,8,utf8_decode($newDate),1,"","L",true);
            $pdf::cell(38,8,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(15,8,utf8_decode($reg->num_documento),1,"","R",true);
            $pdf::cell(15,8,utf8_decode($reg->timbrado),1,"","R",true);
            $pdf::cell(20,8,utf8_decode($reg->num_comprobante),1,"","R",true);
            $pdf::cell(15,8,utf8_decode(number_format(($reg->exentas), 0, ",", ".")  ),1,"","R",true);
            $pdf::cell(15,8,utf8_decode(number_format(($reg->impuesto5), 0, ",", ".")  ),1,"","R",true);
            $pdf::cell(15,8,utf8_decode(number_format(($reg->impuesto10), 0, ",", ".")  ),1,"","R",true);
            $pdf::cell(15,8,utf8_decode(number_format(($reg->descuento), 0, ",", ".")  ),1,"","R",true);
            $pdf::cell(15,8,utf8_decode(number_format(($reg->total_ingreso), 0, ",", ".")  ),1,"","R",true);
            $pdf::Ln(); 
         }

         // Obtiene el objeto del Usuario Autenticado
         $user = Auth::user();
         //obener el id y cargar en una variable
         $usuarioActual = $user->id;
         $ruta = 'pdf/'.$usuarioActual.'_reporteingreso.pdf';
         $pdf::Ln();
         $pdf::Output($ruta, 'F');
         return view('compras.ingreso.vistapdf');
    }
}

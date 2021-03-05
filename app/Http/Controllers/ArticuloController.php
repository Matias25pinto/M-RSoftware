<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Http\Requests\ArticuloFormRequest;
use sisMR\Articulo;
use DB;
use Illuminate\Support\Facades\Auth;
use Fpdf;


class ArticuloController extends Controller
{
    public function __construct()
    {
         $this->middleware(['auth',
            'verificarPermiso:administrador,deposito'
            ]);
        
    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));
            if(is_numeric($query)){
                $articulos=DB::table('articulo as a')
                ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.precio_venta','a.imagen','a.estado','unidad_medida')
                ->where('a.nombre','=',$query)
                ->orwhere('a.codigo','=',$query)
                ->orderBy('a.idarticulo','desc')
                ->paginate(10);
            }
            else{
                $articulos=DB::table('articulo as a')
                ->join('categoria as c','a.idcategoria','=','c.idcategoria')
                ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.precio_venta','a.imagen','a.estado','unidad_medida')
                ->where('a.nombre','LIKE','%'.$query.'%')
                ->orwhere('a.codigo','=',$query)
                ->orderBy('a.idarticulo','desc')
                ->paginate(10);
            }
            
           
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
        }
        
    }
    public function create()
    {
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        $articulos=DB::table('articulo')->where('estado','=','Activo')->get();
        return view("almacen.articulo.create",["categorias"=>$categorias, "articulos"=>$articulos]);
    }
    public function store (ArticuloFormRequest $request)
    {
        $articulo=new Articulo;
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->stock_minimo=$request->get('stock_minimo');
        $articulo->precio_compra=$request->get('precio_compra');
        $articulo->unidad_medida=$request->get('unidad_medida');
        $articulo->precio_venta=$request->get('precio_venta');
        $articulo->precio_venta2=$request->get('precio_venta2');
        $articulo->precio_venta3=$request->get('precio_venta3');
        $articulo->impuesto=$request->get('impuesto');
        $articulo->estado='Activo';

        if (Input::hasFile('imagen')){
        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
            $articulo->imagen=$file->getClientOriginalName();
        }
        $articulo->save();
        $personas=DB::table('persona')->where('tipo_persona','=','Proveedor')->get();
        
             return Redirect::action('ArticuloController@index');
    }
    public function show($id)
    {
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);
    }
    public function edit($id)
    {
        $articulo=Articulo::findOrFail($id);
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);
    }
    
    
    public function update(ArticuloFormRequest $request,$id)
    {
        $articulo=Articulo::findOrFail($id);

        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock_minimo=$request->get('stock_minimo');
        $articulo->unidad_medida=$request->get('unidad_medida');
        $articulo->precio_compra=$request->get('precio_compra');
        $articulo->precio_venta=$request->get('precio_venta');
        $articulo->precio_venta2=$request->get('precio_venta2');
        $articulo->precio_venta3=$request->get('precio_venta3');
        $articulo->impuesto=$request->get('impuesto');
        $articulo->estado='Activo';

        if (Input::hasFile('imagen')){
        	$file=Input::file('imagen');
        	$file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
        	$articulo->imagen=$file->getClientOriginalName();
        }

        $articulo->update();
        return Redirect::to('almacen/articulo');
    }
    public function destroy($id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->Estado='Inactivo';
        $articulo->update();
        return Redirect::to('almacen/articulo');
    }
    public function reporte(){
         //Obtenemos los registros
         $registros=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.stock_minimo','a.imagen','a.estado','a.precio_compra','a.unidad_medida')
            
            ->orderBy('a.unidad_medida','a.nombre','asc')
            ->get();
            //Obtenemos la suma total
         $sumaTotal=DB::table('articulo as a')
         ->select(DB::raw("SUM(a.precio_compra*a.stock) as Total"))
         ->where('a.unidad_medida','=','unidad')
         ->get();
         $sumaTotalgramos=DB::table('articulo as a')
         ->select(DB::raw("SUM(a.precio_compra*(a.stock/1000)) as Total"))
         ->where('a.unidad_medida','=','gramos')
         ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         
         $pdf::Cell(0,10,utf8_decode("Capital Total"),0,"","C");
         $pdf::Ln();
         foreach ($sumaTotal as $sumreg)
         {
            $pdf::cell(0,9,"Por Unidad: ".number_format($sumreg->Total,0,'','.')." Gs.",0,"","C");
         } 
         foreach ($sumaTotalgramos as $sumreggramos)
         {
            $pdf::Ln();
            $pdf::cell(0,9,"Por Kilo: ".number_format($sumreggramos->Total,0,'','.')." Gs.",0,"","C");
         } 
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190
         $pdf::Cell(0,10,utf8_decode("Listado de Artículos"),0,"","L");
         $pdf::Ln();      
         $pdf::cell(30,8,utf8_decode("Código"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Nombre"),1,"","L",true);
         $pdf::cell(26,8,utf8_decode("Categoría"),1,"","L",true);
         $pdf::cell(27,8,utf8_decode("Precio Compra"),1,"","L",true);
         $pdf::cell(15,8,utf8_decode("Stock"),1,"","L",true);
        
         $pdf::cell(17,8,utf8_decode("Total"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
             if($reg->unidad_medida == 'unidad'){
                $pdf::cell(30,6,utf8_decode($reg->codigo),1,"","L",true);
                $pdf::cell(80,6,utf8_decode($reg->nombre),1,"","L",true);
                $pdf::cell(26,6,utf8_decode($reg->categoria),1,"","L",true);
                $pdf::cell(27,6,number_format($reg->precio_compra,0,'','.'),1,"","L",true);
                $pdf::cell(15,6,utf8_decode($reg->stock.' u'),1,"","L",true);
                
                $pdf::cell(17,6,number_format($reg->stock * $reg->precio_compra,0,'','.'),1,"","L",true);
                $pdf::Ln(); 
             }
             if($reg->unidad_medida == 'gramos'){
                $pdf::cell(30,6,utf8_decode($reg->codigo),1,"","L",true);
                $pdf::cell(80,6,utf8_decode($reg->nombre),1,"","L",true);
                $pdf::cell(26,6,utf8_decode($reg->categoria),1,"","L",true);
                $pdf::cell(27,6,number_format($reg->precio_compra,0,'','.'),1,"","L",true);
                $pdf::cell(15,6,utf8_decode(($reg->stock/1000).' kg'),1,"","L",true);
               
                $pdf::cell(17,6,number_format(($reg->stock/1000) * $reg->precio_compra,0,'','.'),1,"","L",true);
                $pdf::Ln(); 
             }
            
         }

         // Obtiene el objeto del Usuario Autenticado
         $user = Auth::user();
         //obener el id y cargar en una variable
         $usuarioActual = $user->id;
         $ruta = 'pdf/'.$usuarioActual.'_reportearticulos.pdf';
         $pdf::Ln();
         $pdf::Output($ruta, 'F');
         return view('almacen.articulo.vistapdf');
    }
    public function reportestock(){
         //Obtenemos los registros
         $registros=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.stock_minimo','a.imagen','a.estado')
            ->where('a.stock','<=','a.stock_minimo')
            ->where('a.estado','=','Activo')
            ->where('a.unidad_medida','!=','servicio')
            ->orderBy('a.nombre','asc')
            ->get();

         $pdf = new Fpdf();
         $pdf::AddPage();
         $pdf::SetTextColor(35,56,113);
         $pdf::SetFont('Arial','B',11);
         $pdf::Cell(0,10,utf8_decode("Listado de Artículos con STOCK bajo"),0,"","C");
         $pdf::Ln();
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda 
         $pdf::SetFont('Arial','B',10); 
         //El ancho de las columnas debe de sumar promedio 190        
         $pdf::cell(30,8,utf8_decode("Código"),1,"","L",true);
         $pdf::cell(80,8,utf8_decode("Nombre"),1,"","L",true);
         $pdf::cell(65,8,utf8_decode("Categoría"),1,"","L",true);
         $pdf::cell(15,8,utf8_decode("Stock"),1,"","L",true);
         
         $pdf::Ln();
         $pdf::SetTextColor(0,0,0);  // Establece el color del texto 
         $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
         $pdf::SetFont("Arial","",9);
         
         foreach ($registros as $reg)
         {
            $pdf::cell(30,6,utf8_decode($reg->codigo),1,"","L",true);
            $pdf::cell(80,6,utf8_decode($reg->nombre),1,"","L",true);
            $pdf::cell(65,6,utf8_decode($reg->categoria),1,"","L",true);
            $pdf::cell(15,6,utf8_decode($reg->stock),1,"","L",true);
            $pdf::Ln(); 
         }

                // Obtiene el objeto del Usuario Autenticado
                $user = Auth::user();
                //obener el id y cargar en una variable
                $usuarioActual = $user->id;
                $ruta = 'pdf/'.$usuarioActual.'_reportearticulos.pdf';
                $pdf::Ln();
                $pdf::Output($ruta, 'F');
                return view('almacen.articulo.vistapdf');
    }

}

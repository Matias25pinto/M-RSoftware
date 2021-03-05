<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Ajuste;
use sisMR\Http\Requests\AjusteFormRequest;
use Carbon\Carbon;
use DB;

class AjusteController extends Controller
{
     public function __construct()
    {
        $this->middleware(['auth',
            'verificarPermiso:administrador,deposito'
            ]);
    }
    public function index(Request $request)
    {
    	if($request){
    		$query=trim($request->get('searchText'));
    		$ajustes=DB::table('ajuste as a')
    		->join('articulo as ar', 'a.idarticulo','=','ar.idarticulo')
    		->select('a.idajuste','a.fecha_hora','a.cantidad','a.estado','a.detalle','ar.nombre','codigo','ar.imagen')
    		->where('ar.nombre','LIKE','%'.$query.'%')
            ->orwhere('a.fecha_hora','LIKE','%'.$query.'%')
            ->orwhere('ar.codigo','LIKE','%'.$query.'%')
    		
    		->orderBy('a.idajuste','desc')
    		->paginate(10);
    		return view("almacen.ajuste.index",["ajustes"=>$ajustes,"searchText"=>$query]);
    		
    	}
    }
     public function create()
    {
    	$articulos=DB::table('articulo')->get();
      
    	return view("almacen.ajuste.create", ["articulos"=>$articulos]);
    }
    public function store(AjusteFormRequest $request)
    {
    	
    try {
            
            DB::beginTransaction();
    		
    		$ajustes=new Ajuste;
    		$ajustes->idarticulo=$request->get('idarticulo');
    		
    		$ajustes->estado=$request->get('estado');
    		$mytime = Carbon::now('America/Asuncion');
    		$ajustes->fecha_hora=$mytime->toDateTimeString();
    		$ajustes->detalle=" ";
            $ajustes->cantidad=$request->get('cantidad'); 
            
    		    if ($ajustes->cantidad > 0) {
                    $ajustes->stock='Aumento el stock';
                }
                if ($ajustes->cantidad < 0) {
                    $ajustes->stock='Disminuye el stock';
                }
                if ($ajustes->cantidad == 0) {
                    $ajustes->stock='No cambio el stock';
                }


    		$ajustes->save();
    	
    		DB::commit();
        } catch (Exception $e) 
        {
            DB::rollback();
        }
        if ($request->get('cargainicial')) {
            return Redirect::to('almacen/articulo');
        }
        else{
            return Redirect::to('almacen/ajuste');
        }
        
 

    }

    public function show($id)
    {
    	$ajuste=DB::table('ajuste as aj')
    	->join('articulo as ar','aj.idarticulo','=','ar.idarticulo')
    	->select('aj.idarticulo','aj.idajuste','aj.cantidad','aj.estado','aj.fecha_hora','aj.detalle','aj.stock','ar.nombre')
    	->where('aj.idajuste','=',$id)
    	->first();
    	return view("almacen.ajuste.show",["ajuste"=>$ajuste]);
    }
    
}
<?php

namespace sisMR\Http\Controllers;

use Illuminate\Http\Request;

use sisMR\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisMR\Caja;
use sisMR\User;

use sisMR\Http\Requests\CajaFormRequest;
use sisMR\Http\Requests\CajaCierreFormRequest;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Cajacontroller extends Controller
{
     public function __construct()
    {
       $this->middleware(['auth',
            'verificarPermiso:administrador,ventas'
            ]);
    }
    public function index(Request $request){
    	 if ($request)
        {   
            if (Auth::user()->permisos == "administrador") {
                $query=trim($request->get('searchText'));
                $caja=DB::table('caja as c') 
                ->join('users as u','u.id','=','c.idusers')
                ->select('c.idcaja', 'c.monto_apertura_caja', 'c.fecha_hora_apertura', 'c.monto_cierre_caja', 'c.fecha_hora_cierre', 'c.idusers', 'u.name','c.ventas','u.apertura_caja')
                ->where('c.idusers','LIKE','%'.$query.'%')
                ->orderBy('c.idcaja','desc')
                ->paginate(10);
            }
            else{
                $usuario = Auth::user()->id;
                $query=trim($request->get('searchText'));
                $caja=DB::table('caja as c') 
                ->join('users as u','u.id','=','c.idusers')
                ->select('c.idcaja', 'c.monto_apertura_caja', 'c.fecha_hora_apertura', 'c.monto_cierre_caja', 'c.fecha_hora_cierre', 'c.idusers', 'u.name','c.ventas','u.apertura_caja')
                ->where('c.idusers','=',$usuario)
                ->orderBy('c.idcaja','desc')
                ->paginate(10);
            }
            return view('ventas.caja.index',["caja"=>$caja,"searchText"=>$query]);
        }
    }
    public function create(){

    	return view("ventas.caja.create");
    }
    public function store (CajaFormRequest $request)
    {

        //crear los datos de la caja
    	$caja= new Caja;
    	$caja->idusers=$request->get('idusers');
        
        $billete_100mil=100000*$request->get('100mil');
        $billete_50mil=50000*$request->get('50mil');
        $billete_20mil=20000*$request->get('20mil');
        $billete_10mil=10000*$request->get('10mil');
        $billete_5mil=5000*$request->get('5mil');
        $billete_2mil=2000*$request->get('2mil');
        $moneda_1000=1000*$request->get('moneda_1000');
        $moneda_500=500*$request->get('moneda_500');
        $moneda_100=100*$request->get('moneda_100');
        $moneda_50=50*$request->get('moneda_50');

        $caja->monto_apertura_caja=$billete_100mil+$billete_50mil+$billete_20mil+$billete_10mil+$billete_5mil+$billete_2mil+$moneda_1000+$moneda_500+$moneda_100+$moneda_50;
        $mytime = Carbon::now('America/Asuncion');
	    $caja->fecha_hora_apertura=$mytime->toDateTimeString();
        $caja->save();
        //asignar la apertura y el idcaja al usuario
        $usuario= User::findOrFail($request->get('idusers'));
        $usuario->apertura_caja = 1;
        $usuario->idcaja = $caja->idcaja;
        $usuario->update();

        
    	return Redirect::to('ventas/venta');
    }
    public function edit($id)
    {   
            $datos=DB::table('caja')->where('idcaja','=',$id)->first(); 
            $ventas=DB::table('venta as v')
            ->join('caja as c','v.idusers','=','c.idusers')
            ->select(DB::raw('sum(v.total_venta) as ventas'))
            ->where('c.fecha_hora_cierre','=',null)
            ->where('v.fecha_hora','>=',$datos->fecha_hora_apertura)
            ->where('v.estado','=','ACTIVO')
            ->where('v.idcaja','=',$id)
            ->groupBy()
            ->get();
    	  return view("ventas.caja.edit",["caja"=>Caja::findOrFail($id),"ventas"=>$ventas,"datos"=>$datos]);
    }
    public function update(CajaCierreFormRequest $request,$id){
    	$caja=Caja::findOrFail($id);
        //cambiar el estado del usuario si el usuario conectado es igual al usuario de de caja
        $idconectado = Auth::user()->id;
        if($caja->idusers == $idconectado){
            $billete_100mil=100000*$request->get('100mil');
            $billete_50mil=50000*$request->get('50mil');
            $billete_20mil=20000*$request->get('20mil');
            $billete_10mil=10000*$request->get('10mil');
            $billete_5mil=5000*$request->get('5mil');
            $billete_2mil=2000*$request->get('2mil');
            $moneda_1000=1000*$request->get('moneda_1000');
            $moneda_500=500*$request->get('moneda_500');
            $moneda_100=100*$request->get('moneda_100');
            $moneda_50=50*$request->get('moneda_50');

            $caja->monto_cierre_caja=$billete_100mil+$billete_50mil+$billete_20mil+$billete_10mil+$billete_5mil+$billete_2mil+$moneda_1000+$moneda_500+$moneda_100+$moneda_50;
            $mytime = Carbon::now('America/Asuncion');
	        $caja->fecha_hora_cierre=$mytime->toDateTimeString();
            $caja->ventas=$request->get('ventas');
            $caja->update();

            $usuario= User::findOrFail($caja->idusers);
            $usuario->apertura_caja = 0 ;
            $usuario->update();

            return Redirect::to('ventas/caja');
        }
        else{
            echo 'NO ES EL USUARIO QUE ABRIO LA CAJA';
        }
    }
    public function show ($id)
    {
        $datos=DB::table('caja')->where('idcaja','=',$id)->first(); 
       
        $usuario= User::findOrFail($datos->idusers);
    	 return view("ventas.caja.show",["usuario"=>$usuario,"datos"=>$datos]);
    }
}

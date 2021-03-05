<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('auth/login');
});
Route::get('/acerca', function () {
    return view('acerca');
});

Route::resource('almacen/categoria','CategoriaController');
Route::resource('almacen/articulo','ArticuloController');
Route::resource('almacen/ajuste','AjusteController');
Route::resource('ventas/cliente','ClienteController');
Route::resource('compras/proveedor','ProveedorController');
Route::resource('compras/ingreso','IngresoController');
Route::resource('ventas/venta','VentaController');
Route::resource('pago/salario','PagoController');
Route::resource('reportes/venta','ReporteController');
Route::resource('ventas/caja','Cajacontroller');
Route::resource('ventas/vendedores','VendedoresController');
Route::resource('seguridad/usuario','UsuarioController');

Route::resource('actualizar/ruc','ActualizarRucController');

Route::resource('reportes/pdf','ReportePdfController');
Route::auth();

	Route::get('/home', 'HomeController@index');

//Reportes
Route::get('reportecategorias', 'CategoriaController@reporte');
Route::get('reportearticulos', 'ArticuloController@reporte');
Route::get('reportearticulosstock', 'ArticuloController@reportestock');
Route::get('reporteclientes', 'ClienteController@reporte');

//actualiza la lista de clientes con la lista de la set
Route::get('actualizarclientes/{nro_ruc}', 'ActualizarRucController@actualizarclientes');
//actualiza la lista de proveedores con la lista de la set
Route::get('actualizarproveedores/{nro_ruc}', 'ActualizarRucController@actualizarproveedores');
//actualiza la BASE DE LA SET
Route::get('base_set/{nro_ruc}', 'ActualizarRucController@base_set');

Route::get('reportevendedores', 'VendedoresController@reporte');
Route::get('reporteproveedores', 'ProveedorController@reporte');
Route::get('reporteventas', 'VentaController@reporte');

//reporte ventas pdf
Route::get('reporteganancias/{fechaInicio}/{fechaFin}','ReportePdfController@ventas');

//reporte facturas pdf
Route::get('reportegananciasfactura/{fechaInicio}/{fechaFin}','ReportePdfController@facturaspdf');

//reporte compras pdf
Route::get('reportecompraspdf/{fechaInicio}/{fechaFin}','ReportePdfController@compraspdf');

//reporte hechauka ventas
Route::get('reporteventas/{fechaInicio}/{fechaFin}/{formato}','ReporteController@hechaukaventas');
//reporte hechauka compras
Route::get('reportecompras/{fechaInicio}/{fechaFin}/{formato}','ReporteController@hechaukacompras');
//reporte resumen
Route::get('reporteresumen/{fechaInicio}/{fechaFin}/{formato}','ReporteController@resumen');
//reporte ventas mensual
Route::get('reporteventasmensual/{fechaInicio}/{fechaFin}/{formato}','ReporteController@ventas');



Route::get('reportegananciames', 'VentaController@gananciames');
Route::get('reporteventa/{id}', 'VentaController@reportec');
Route::get('ticket/{id}', 'VentaController@ticket');
Route::get('reporteingresos', 'IngresoController@reporte'); 
Route::get('reporteingreso/{id}', 'IngresoController@reportec');
Route::get('reportepago/{fechaInicio}/{fechaFin}/{idusers}/{comision}','PagoController@reporte');
Route::get('reportepaDetalles/{idpago}', 'PagoController@detalles');
Route::get('/{slug?}', 'HomeController@index');
Route::resource('cargar/ventas','Cventascontroller');
    Route::get('ventas/show/{id}', 'Cventascontroller@show');
    Route::get('ventas/edit/{id}', 'Cventascontroller@edit');
    Route::get('ventas/update/{id}', 'Cventascontroller@update');
    Route::delete('ventas/modal/{id}', 'Cventascontroller@destroy');



 
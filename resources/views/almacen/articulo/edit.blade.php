@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Artículo: {{ $articulo->nombre}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>
			{!!Form::model($articulo,['method'=>'PATCH','route'=>['almacen.articulo.update',$articulo->idarticulo],'files'=>'true'])!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="nombre">Nombre</label>
            	<input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control">
                <input type="hidden" name="id" required value="{{$articulo->idarticulo}}">
            </div>
    	</div>
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
    			<label>Categoría</label>
    			<select name="idcategoria" class="form-control selectpicker" data-live-search="true">
    				@foreach ($categorias as $cat)
    					@if ($cat->idcategoria==$articulo->idcategoria)
                       <option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
                       @else
                       <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                       @endif
    				@endforeach
    			</select>
    		</div>
    	</div>
    	
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label>Impuesto</label>
                    <select name="impuesto" class="form-control">
                        @if($articulo->impuesto==5)

                                <option value="10">10%</option>
                                <option value="5" selected>5%</option>
                                <option value="0">Exento</option>
                        @endif
                        @if($articulo->impuesto==0)

                                <option value="10">10%</option>
                                <option value="5">5%</option>
                                <option value="0" selected>Exento</option>
                        @endif
                        @if($articulo->impuesto==10)

                                <option value="10" selected>10%</option>
                                <option value="5" >5%</option>
                                <option value="0">Exento</option>
                        @endif
                    </select>
                </div>
            </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label>Unidad de Medida</label>
                    <select name="unidad_medida" class="form-control">
                        @if($articulo->unidad_medida=="servicio")
                            <option value="servicio" selected>Servicio</option>
                            <option value="unidad">Unidad</option>
                            <option value="gramos">Gramos</option>
                        @endif
                            
                        @if($articulo->unidad_medida=="unidad")
                            <option value="servicio">Servicio</option>
                            <option value="unidad" selected>Unidad</option>
                            <option value="gramos">Gramos</option>
                                
                        @endif
                        @if($articulo->unidad_medida=="gramos")
                            <option value="servicio">Servicio</option>
                            <option value="unidad">Unidad</option>
                            <option value="gramos" selected>Gramos</option>
                        @endif
                    </select>
                </div>
            </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_compra">Precio compra</label>
                <input type="text" id="price1" name="number1" required value="{{$articulo->precio_compra}}" class="form-control" placeholder="Precio compra...">
                  <input type="hidden" name="precio_compra" id="precio_compra" required>
            </div>
        </div>
         <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta">Precio Venta Unitario</label>
                <input type="text" id="price2" name="number2" required value="{{$articulo->precio_venta}}" class="form-control" placeholder="Precio venta 1...">
                <input type="hidden" name="precio_venta" id="precio_venta" required >
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta2">Precio Venta Mayorista</label>
                <input type="text" id="price3" name="number3" required value="{{$articulo->precio_venta2}}" class="form-control" placeholder="Precio venta 2...">
                <input type="hidden" name="precio_venta2" id="precio_venta2" required >
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta3">Precio Venta Especial</label>
                <input type="text" id="price4" name="number4" required value="{{$articulo->precio_venta3}}" class="form-control" placeholder="Precio venta 3...">
                <input type="hidden" name="precio_venta3" id="precio_venta3" required >
            </div>
        </div>
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="stock_minimo">Stock Minimo</label>
            	<input type="text" name="stock_minimo" value="{{$articulo->stock_minimo}}" class="form-control" placeholder="Stock minimo del artículo...">
            </div>
    	</div>
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="imagen">Imagen</label>
            	<input type="file" name="imagen" class="form-control">
            	@if ($articulo->imagen != "")
            		<img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" height="300px" width="300px">
            	@endif
            </div>
    	</div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="codigo">Código</label>
            	<input type="text" name="codigo" id="codigobar" required value="{{$articulo->codigo}}" class="form-control">
                <br>
                <button class="btn btn-success" type="button" onclick="generarBarcode()">Generar</button>
                <button class="btn btn-info" onclick="imprimir()"type="button">imprimir</button>
                <div id="print">
                    <svg id="barcode"></svg>
                </div>
                
            </div>
    	</div>

    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<button class="btn btn-primary" id="guardar" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>
			{!!Form::close()!!}		
            
@push ('scripts')
<script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script src="{{asset('js/jquery.PrintArea.js')}}"></script>
<script>
// CONVERTIR A VALOR MONEDA
 $(function(){

                // PRECIO compra
                $('#price1').on('change',function(){
                    console.log('Change event.');
                    var val1 = $('#price1').val();
                    
                    $('#precio_compra').val(val1);
                    
                });
                
                $('#price1').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price1').number( true, 0 );
                
                $('#guardar').on('click',function(){
                    
                    var val1 = $('#price1').val();
                    
                    $('#precio_compra').val(val1);

                    var val2 = $('#price2').val();
                    
                    $('#precio_venta').val(val2);

                    var val3 = $('#price3').val();
                    
                    $('#precio_venta2').val(val3);

                    var val4 = $('#price4').val();
                    
                    $('#precio_venta3').val(val4);
                });

                // PRECIO venta 1
                $('#price2').on('change',function(){
                    console.log('Change event.');
                    var val2 = $('#price2').val();
                    
                    $('#precio_venta').val(val2);
                    
                });
                
                $('#price2').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price2').number( true, 0 );
                
                

                 // PRECIO venta 2
                $('#price3').on('change',function(){
                    console.log('Change event.');
                    var val3 = $('#price3').val();
                    
                    $('#precio_venta2').val(val3);
                    
                });
                
                $('#price3').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price3').number( true, 0 );
                
                

                // PRECIO venta 3
                $('#price4').on('change',function(){
                    console.log('Change event.');
                    var val4 = $('#price4').val();
                    
                    $('#precio_venta3').val(val4);
                    
                });
                
                $('#price4').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price4').number( true, 0 );
                
            });
function generarBarcode()
{   
    codigo=$("#codigobar").val();
    JsBarcode("#barcode", codigo, {
    format: "EAN13",
    font: "OCRB",
    fontSize: 18,
    textMargin: 0
    });
}
$('#liAlmacen').addClass("treeview active");
$('#liArticulos').addClass("active");


//Código para imprimir el svg
function imprimir()
{
    $("#print").printArea();
}
generarBarcode();


</script>
@endpush
@endsection
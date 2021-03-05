@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Artículo</h3>
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
			{!!Form::open(array('url'=>'almacen/articulo','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="nombre">Nombre</label>
            	<input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
                <input type="hidden" name="id" class="form-control">
            </div>
    	</div>
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
    			<label>Categoría</label>
    			<select name="idcategoria" class="form-control selectpicker" data-live-search="true">
    				@foreach ($categorias as $cat)
                       <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
    				@endforeach
    			</select>
    		</div>
    	</div>
    	
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label>Impuesto</label>
                <select name="impuesto" class="form-control">
                    <option value="10">10%</option>
                    <option value="5">5%</option>
                    <option value="0">Exento</option>
                </select>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <div class="form-group">
                    <label>Unidad de Medida</label>
                    <select name="unidad_medida" class="form-control">
                            <option value="servicio">Servicio</option>
                            <option value="unidad">Unidad</option>
                            <option value="gramos">Gramos</option>
                    </select>
                </div>
            </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_compra">Precio compra</label>
                <input type="text" required id="price1" name="number1" required placeholder="Precio compra..." class="form-control" value="{{old('number1')}}" >

                <input type="hidden" name="precio_compra" id="precio_compra" required>

            </div>
        </div>
         <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta">Precio Venta Unitario</label>
                 <input  type="text" required id="price2" name="number2" required placeholder="Precio Venta 1..." class="form-control" value="{{old('number2')}}">
                <input type="hidden" name="precio_venta" id="precio_venta" required >
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta2">Precio Venta Mayorista</label>
                 <input  type="text" required id="price3" name="number3" required placeholder="Precio Venta 2..." class="form-control" value="{{old('number3')}}">
                <input type="hidden" name="precio_venta2" id="precio_venta2" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="precio_venta">Precio Venta Especial</label>
                 <input  type="text" required id="price4" name="number4" required placeholder="Precio Venta 3..." class="form-control" value="{{old('number4')}}">
                <input type="hidden" name="precio_venta3" id="precio_venta3" required>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" name="stock" required value="{{old('stock')}}" class="form-control" placeholder="Stock...">
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="stock_minimo">Stock Minimo</label>
            	<input type="text" name="stock_minimo"  value ="1"class="form-control" placeholder="Stock minimo del artículo...">
            </div>
    	</div>
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="imagen">Imagen</label>
            	<input type="file" name="imagen" class="form-control">
            </div>
    	</div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" id="codigobar" required value="{{old('codigo')}}" class="form-control" placeholder="Código del artículo...">
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
            	<button id="guardar" class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>
            
            
            

			{!!Form::close()!!}
@push ('scripts')
<script src="{{asset('js/JsBarcode.all.min.js')}}"></script>
<script src="{{asset('js/jquery.PrintArea.js')}}"></script>
<script>
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

</script>
@endpush

@endsection
@extends ('layouts.admin')
@section ('contenido')

    <div class="row">
		<div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
			<h3>Editar Factura</h3>
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
    {!!Form::model($factura,['method'=>'PATCH','route'=>['ventas.update',$factura->idfactura],'files'=>'true'])!!}
            {{Form::token()}}
			<input type="hidden" name="cliente"  value="{{$factura->idcliente}}" class="form-control">
	<div class="row">  
		<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre: {{$factura->idfactura}}</label>
				<label for="nombre">{{$factura->nombre}}</label>
            </div>
        </div>
	   
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="nombre">RUC: </label>
				<label for="nombre">{{$factura->num_documento}}</label>
            </div>
        </div>
		<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="nombre">Dirección: </label>
				<label for="nombre">{{$factura->direccion}}</label>
            </div>
        </div>
	</div>      	
    
    <div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Fecha: AÑO-MES-DIA</label>
				
                <input type="text" name="fechaInicio"  value="{{$factura->fecha_hora}}" class="form-control" placeholder="Número Factura...">
            	
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">N° Factura</label>
            	<input  type="text" onkeypress="return validarnrof(event)" name="nro_factura" id="nro_factura" required value="{{$factura->nro_factura}}" class="form-control" placeholder="Número Factura...">
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="timbrado">Timbrado</label>
				
                <input type="text" name="timbrado"  value="{{$factura->timbrado}}" class="form-control" placeholder="Número Factura...">
            	
            </div>
    	</div>        
    </div>
    <div class="row">
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="exentas">Subtotal Exentas</label>
					
					<input type="hidden" id="exentas" name="exentas"  value="{{$factura->exentas}}" class="form-control" >
					<input onkeypress="return event.keyCode!=13" value="{{$factura->exentas}}"  type="text" required id="price1" name="number"  placeholder="Exentas..." class="form-control">
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="impuesto5">Subtotal 5%</label>

					<input type="hidden" id="impuesto5" name="impuesto5"  value="{{$factura->impuesto5}}" class="form-control" >
					<input onkeypress="return event.keyCode!=13" value="{{$factura->impuesto5}}"  type="text" required id="price2" name="number"  placeholder="Subtotal 5%..." class="form-control">
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="impuesto10">Subtotal 10%</label>
					
					<input type="hidden" id="impuesto10" name="impuesto10"  value="{{$factura->impuesto10 }}" class="form-control">
					<input onkeypress="return event.keyCode!=13" value="{{$factura->impuesto10}}"  type="text" required id="price3" name="number"  placeholder="Subtotal 10%..." class="form-control">
            	    
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="total_venta">Total ingreso</label>
					
					<input type="hidden" id="total_venta" name="total_venta"  value="{{$factura->total_venta}}" class="form-control" placeholder="Número Factura...">
					<input onkeypress="return event.keyCode!=13" value="{{$factura->total_venta}}"  type="text" required id="price4" name="number"  placeholder="Subtotal 10%..." class="form-control">
            	    
                </div>
    	    </div>
        </div>
        
		<div class="col-lg-6 col-ms-6 col-md-6 col-xs-12">	
				<div class="form-group">
					<button class="btn btn-primary" type="submit">Guardar</button>
					<button class="btn btn-danger" type="reset">Cancelar</button>
				</div>
			</div>
		{!!Form::close()!!}
    
@push ('scripts')
<script>
//formatear subtotal exentas
$(function(){
                // Set up the number formatting.
                $('#price1').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price1').val();
                    
                    $('#exentas').val(val);
                    
                });
                
                $('#price1').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price1').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price1').val();
                    
                    $('#exentas').val(val);
                });

                
            });
            //formatear subtotal 5%
            $(function(){
                // Set up the number formatting.
                $('#price2').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price2').val();
                    
                    $('#impuesto5').val(val);
                    
                });
                
                $('#price2').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price2').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price2').val();
                    
                    $('#impuesto5').val(val);
                });

                
            });
            //formatear subtotal 10%
            $(function(){
                // Set up the number formatting.
                $('#price3').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price3').val();
                    
                    $('#impuesto10').val(val);
                    
                });
                
                $('#price3').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price3').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price3').val();
                    
                    $('#impuesto10').val(val);
                });

                
            });
            //formatear Total a pagar 
            $(function(){
                // Set up the number formatting.
                $('#price4').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price4').val();
                    
                    $('#total_venta').val(val);
                    
                });
                
                $('#price4').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price4').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price4').val();
                    
                    $('#total_venta').val(val);
                });

                
            });
    //formatear nro factura, evita que se escriba un valor que no sea numero o guion
    function validarnrof(e){
              var val1 = $('#nro_factura').val();
              
              tecla = (document.all) ? e.keyCode : e.which;
              tecla = String.fromCharCode(tecla)
              if(val1.length == 3 || val1.length == 7){
                
                var val = $('#nro_factura').val();
            
                $('#nro_factura').val(val.concat('-'));
                
            }
              return /^[0-9\-]+$/.test(tecla);
      }
$('#licontabilidad').addClass("treeview active");
$('#lifacturasventas').addClass("active");
</script>
@endpush
@endsection
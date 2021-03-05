@extends ('layouts.admin')
@section ('contenido')
<div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Cliente</label>
            	<p>{{$factura->nombre}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">N° RUC</label>
            	<p>{{$factura->num_documento}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Dirección</label>
            	<p>{{$factura->direccion}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="telefono">Teléfono</label>
            	<p>{{$factura->telefono}}</p>
            </div>
    	</div>
    
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Fecha</label>
                <?php
                    //con codigo php directo se formatea la fecha y se pasa por $date 
                    $date = new DateTime($factura->fecha_hora);
                    
                ?>
                <p>{{$date->format('d-m-Y')}}</p>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">N° Factura</label>
            	<p>{{$factura->nro_factura}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Timbrado</label>
            	<p>{{$factura->timbrado}}</p>
            </div>
    	</div>        
    </div>
    <div class="row">
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">Subtotal Exentas</label>
            	    <p>{{number_format($factura->exentas ,0,'','.')}}</p>
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">Subtotal 5%</label>
            	    <p>{{number_format($factura->impuesto5 ,0,'','.')}}</p>
                </div>
    	    </div>
					<?php 
						 //calcular iva
             $iva5 = round($factura->impuesto5/21, 0, PHP_ROUND_HALF_UP);
             $iva10 = round($factura->impuesto10/11, 0, PHP_ROUND_HALF_UP);
					?>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">IVA 5%</label>
            	    <p>{{number_format($iva5 ,0,'','.')}}</p>
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">Subtotal 10%</label>
            	    <p>{{number_format($factura->impuesto10 ,0,'','.')}}</p>
                </div>
    	    </div>
					<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">IVA 10%</label>
            	    <p>{{number_format($iva10  ,0,'','.')}}</p>
                </div>
    	    </div>
            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		    <div class="form-group">
            	    <label for="proveedor">Total ingreso</label>
            	    <p>{{number_format($factura->total_venta ,0,'','.')}}</p>
                </div>
    	    </div>
    </div>

    
@push ('scripts')
<script>
$('#licontabilidad').addClass("treeview active");
$('#lifacturasventas').addClass("active");
</script>
@endpush
@endsection
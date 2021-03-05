@extends ('layouts.admin')
@section ('contenido')
<div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Proveedor</label>
            	<p>{{$ingreso->nombre}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">N° RUC</label>
            	<p>{{$ingreso->num_documento}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Dirección</label>
            	<p>{{$ingreso->direccion}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="telefono">Teléfono</label>
            	<p>{{$ingreso->telefono}}</p>
            </div>
    	</div>
    
    </div>
    
    <div class="row">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                <label for="proveedor">Fecha</label>
                <?php
                    //con codigo php directo se formatea la fecha y se pasa por $date 
                    $date = new DateTime($ingreso->fecha_hora);
                    
                ?>
                <p>{{$date->format('d-m-Y')}}</p>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">N° Factura</label>
            	<p>{{$ingreso->num_comprobante}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Timbrado</label>
            	<p>{{$ingreso->timbrado}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Total ingreso</label>
            	<p>{{number_format($ingreso->total_ingreso ,0,'','.')}}</p>
            </div>
    	</div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Descuento Total</label>
            	<p>{{number_format($ingreso->descuento ,0,'','.')}}</p>
            </div>
    	</div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">            

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                            
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio compra</th>
                            <th>Descuento p/Art.</th>
                            <th>Impuesto</th>
                            <th>Bonificación</th>
                        </thead>
                        <tfoot>
                            <th></th>
                        </tfoot>
                        <tbody>
                            @foreach($detalles as $det)
                            <tr>
                                <td>{{$det->articulo}}</td>
                                <td>{{$det->cantidad - $det->bonificacion}}</td>
                                <td>{{number_format($det->precio_compra ,0,'','.')}}</td>
                                <td>{{number_format($det->descuento ,0,'','.')}}</td>
                                @IF($det->impuesto == 0)
                                    <td>Exentas</td>
                                @ELSE
                                    <td>{{$det->impuesto}} %</td>
                                @ENDIF
                                
                                <td>{{number_format($det->bonificacion ,0,'','.')}}</td>
                            </tr>
                            @endforeach
                            <tr>
                            <thead style="background-color:#D1EBF7">
                            <th>Total Exentas: {{number_format($ingreso->exentas ,0,'','.')}}</th>
                            <th>Total 5%: {{number_format($ingreso->impuesto5 ,0,'','.')}}</th>
                            <th>Total 10%: {{number_format($ingreso->impuesto10 ,0,'','.')}}</th>
                            <th>TOTAL: {{number_format($ingreso->total_ingreso ,0,'','.')}}</th>
                        </thead>
                            </tr>   
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    	
    </div>   
@push ('scripts')
<script>
$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection
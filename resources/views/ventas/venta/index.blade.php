@extends ('layouts.admin')
@section ('contenido')

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Ventas <a href="venta/create"><button id="nuevo" class="btn btn-success">Nueva Venta</button></a>
		
		<a href="caja/create"><button id="caja" class="btn btn-danger" onclick="evaluar()" >Apertura de Caja</button></a>
		</h3>
		
		@include('ventas.venta.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Venta</th>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Comprobante</th> 
					<th>Total</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($ventas as $ven)
				<tr> 
					<td>{{ $ven->idventa}}</td>
					<td>{{ $ven->fecha_hora}}</td>
					<td>{{ $ven->nombre}}</td>
					<td>{{ $ven->tipo_comprobante}}</td>
					<td>{{ number_format($ven->total_venta,0,'','.')}}</td>
					<td>{{ $ven->estado}}</td>
					<td>
						<a href="{{URL::action('VentaController@show',$ven->idventa)}}"><button class="btn btn-primary">Detalles</button></a>
						<a  href="{{URL::action('VentaController@reportec',$ven->idventa)}}"><button class="btn btn-info">Factura</button></a>
                         <a href="" data-target="#modal-delete-{{$ven->idventa}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
                         <a href="{{URL::action('VentaController@ticket',$ven->idventa)}}"><button class="btn btn-success">Ticket</button></a>

					</td>
				</tr>
				@include('ventas.venta.modal')

				@endforeach
			</table>
		</div>
		{{$ventas->render()}}
	</div>
</div>  
<input type="hidden" name="apertura_caja" id="apertura_caja" value="{{ Auth::user()->apertura_caja }}" >
@push ('scripts')
 
<script>

evaluar();
function evaluar()
  {
    if ($("#apertura_caja").val() == 1)
    {
      	$("#caja").hide();
      	$("#nuevo").show();
      	 
    }
    if ($("#apertura_caja").val() == 0)
    { 
      $("#caja").show();
      $("#nuevo").hide();  
    }
   }
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush

@endsection
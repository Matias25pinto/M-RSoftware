@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Ventas <a href="ventas/create"><button class="btn btn-success">Nuevo</button></a></h3>
		@include('cargar.ventas.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>N° de Factura</th>
					<th>Fecha</th>
					<th>Cliente</th>
					<th>Tipo</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($facturas as $fac)
				<tr>
					<td>{{ $fac->nro_factura}}</td>
					<td>{{ $fac->fecha_hora}}</td>
					<td>{{ $fac->nombre}}</td>
					@if($fac->tipo_documento == 1)
					<td>Factura</td>
					@endif
					@if($fac->tipo_documento == 3)
					<td>Nota Credito</td>
					@endif
					<td>{{ $fac->estado}}</td>
					<td>
						<a href="{{URL::action('Cventascontroller@show',$fac->idfactura)}}"><button class="btn btn-info">Detalles</button></a>
						
						<a href="" data-target="#modal-delete-{{$fac->idfactura}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
					</td>
				</tr>
				@include('cargar.ventas.modal')
				@endforeach
			</table>
		</div>
		{{$facturas->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#licontabilidad').addClass("treeview active");
$('#lifacturasventas').addClass("active");
</script>
@endpush
@endsection
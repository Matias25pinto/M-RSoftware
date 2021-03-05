@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de apertura y cierre de caja</h3>
		@include('ventas.caja.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>N° Apertura</th>
					<th>Usuario</th>
					<th>Monto de Apertura</th>
					<th>Fecha de Apertura</th>
					<th>Monto de Cierre</th>
					<th>Fecha de Cierre</th>
					<th>CIERRE</th>
				</thead>
               @foreach ($caja as $caj)
				<tr>
					<td>{{ $caj->idcaja}}</td>
					<td>{{ $caj->name}}</td>
					<td>{{ number_format(($caj->monto_apertura_caja),0,'','.')}}</td>
					<td>{{ $caj->fecha_hora_apertura}}</td>
					<td>{{ number_format(($caj->monto_cierre_caja),0,'','.')}}</td>
					<td>{{ $caj->fecha_hora_cierre}}</td>
					<td>
						@if($caj->fecha_hora_cierre != NULL)
							<h4>CERRADO</h4>
						@else
							<a href="{{URL::action('Cajacontroller@edit',$caj->idcaja)}}"><button class="btn btn-danger">Cerrar Caja</button></a>
						@endif
					</td>
					<td>
					@if($caj->fecha_hora_cierre != NULL)
						<a href="{{URL::action('Cajacontroller@show',$caj->idcaja)}}"><button class="btn btn-primary">Detalles</button></a>
					@endif
					</td>
				</tr>
				@endforeach
			</table>
		</div>
		{{$caja->render()}}
	</div>
</div>

@push ('scripts')
 
<script>
$('#liVentas').addClass("treeview active");
$('#liCajas').addClass("active");
</script>
@endpush
@endsection
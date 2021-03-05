@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h3>Listado de Ajustes<a href="ajuste/create"><button class="btn btn-success">Nuevo</button></a></h3>
			@include('almacen.ajuste.search')
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead>
						
						<th>Nombre</th>
						<th>Código Barra</th>
						<th>Cantidad</th>
						<th>Imagen</th>
						<th>Opciones</th>
					</thead>
					@foreach ($ajustes as $aj)
					<tr>
						
						<td>{{ $aj->nombre}}</td>
						
						<td>{{ $aj->codigo}}</td>
						
						<td>{{ $aj->cantidad}}</td>
						
						<td>
							@if($aj->imagen)
							<img src="{{asset('imagenes/articulos/'.$aj->imagen)}}" alt="{{ $aj->nombre}}" height="100px" width="100px" class="img-thumbnail">
							@else
							<img src="{{asset('imagenes/articulos/sin_imagen.png')}}" alt="NO TIENE IMAGEN" height="100px" width="100px" class="img-thumbnail">
							@endif
						</td>
						
						<td>
							<a href="{{URL::action('AjusteController@show',$aj->idajuste)}}"><button class="btn btn-primary">Detalles</button></a>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			{{$ajustes->render()}}
		</div>
	</div>

@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liajustes').addClass("active");
</script>
@endpush
@endsection
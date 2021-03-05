@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Artículos <a href="articulo/create"><button class="btn btn-success">Nuevo</button></a> <a href="{{url('reportearticulos')}}"><button class="btn btn-info">Reporte</button></a></a> <a href="{{url('reportearticulosstock')}}"><button class="btn btn-danger">Reporte de Stock bajo</button></a></h3>
		
		@include('almacen.articulo.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Nombre</th>
					<th>Código</th>
					<th>Categoría</th>
					<th>Precio Venta</th>
					<th>Unidad Medida</th>
					<th>Stock</th>
					<th>Imagen</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($articulos as $art)
				<tr>
					<td>{{ $art->nombre}}</td>
					<td>{{ $art->codigo}}</td>
					<td>{{ $art->categoria}}</td>
					<td>{{ number_format($art->precio_venta,0,'','.')}}</td>
					<td>{{ $art->unidad_medida}}</td>
					<td>{{ $art->stock}}</td>
					<td>
						@if($art->imagen)
						<img src="{{asset('imagenes/articulos/'.$art->imagen)}}" alt="{{ $art->nombre}}" height="100px" width="100px" class="img-thumbnail">
						@else
						<img src="{{asset('imagenes/articulos/sin_imagen.png')}}" alt="NO TIENE IMAGEN" height="100px" width="100px" class="img-thumbnail">
						@endif
					</td>
					
					<td>{{ $art->estado}}</td>
					<td>
						<a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a> 
					</td>
				</tr>
				@include('almacen.articulo.modal')
				@endforeach
			</table>
		</div>
		{{$articulos->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liArticulos').addClass("active");
</script>
@endpush
@endsection
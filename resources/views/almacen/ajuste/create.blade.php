@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-ms-6 col-xs-6">
			<h3>Nuevo Ajuste</h3>
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
			{!!Form::open(array('url'=>'almacen/ajuste','method'=>'POST','autocomplete'=>'off', 'files'=>'true'))!!}
			{!!Form::token()!!}
		<div class="row">
			<div class="col-lg-12 col-ms-12 col-md-12 col-xs-12">
				<div class="form-group">
					<label>Articulo</label>
					<select name="idarticulo" class="form-control selectpicker" data-live-search="true">
						@foreach ($articulos as $ar)
							<option value="{{$ar->idarticulo}}">{{$ar->codigo}}  {{$ar->nombre}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-12 col-ms-12 col-md-12 col-xs-12">
				<div class="form-group">
					<label>Estado</label>
					<select name="estado" class="form-control">
							<option value="Activo">Activo</option>
							<option value="Inactivo">Inactivo</option>
					</select>
				</div>
			</div>
			<div class="col-lg-12 col-ms-12 col-md-12 col-xs-12">
				<div class="form-group">
					<label for="cantidad">Cantidad de ajuste</label>
					<input  type="text" name="cantidad" required value="{{old('cantidad}')}}" class="form-control" placeholder="UNIDAD/GRAMOS números (-) para descontar...">
				</div>
			</div>
			<div class="col-lg-12 col-ms-12 col-md-12 col-xs-12">	
				<div class="form-group">
					<button class="btn btn-primary" type="submit">Guardar</button>
					<button class="btn btn-danger" type="reset">Cancelar</button>
				</div>
			</div>
		</div>
			
		

			{!!Form::close()!!}
@endsection
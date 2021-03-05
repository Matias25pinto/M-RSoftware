@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Apertura de Caja</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::open(array('url'=>'ventas/caja','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
            <div class="form-group">
            	<label for="usuario">Usuario:</label>
            	<h4>{{ Auth::user()->name }}</h4>
            	<input type="hidden" name="idusers" class="form-control" value="{{ Auth::user()->id }}">
            </div>
            <label>CANTIDAD DE:</label>
            <div class="form-group">
            	<label for="monto_apertura_caja">Billetes de 100.000 Gs</label>
            	<input type="number" name="100mil" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<label for="monto_apertura_caja">Billetes de 50.000 Gs</label>
            	<input type="number" name="50mil" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<label for="monto_apertura_caja">Billetes de 20.000 Gs</label>
            	<input type="number" name="20mil" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<label for="monto_apertura_caja">Billetes de 10.000 Gs</label>
            	<input type="number" name="10mil" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<label for="monto_apertura_caja">Billetes de 5000 Gs</label>
            	<input type="number" name="5mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 2000 Gs</label>
                  <input type="number" name="2mil" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<label for="monto_apertura_caja">Monedas de 1000 Gs</label>
            	<input type="number" name="moneda_1000" class="form-control" value="0">
            </div>
             <div class="form-group">
            	<label for="monto_apertura_caja">Monedas de 500 Gs</label>
            	<input type="number" name="moneda_500" class="form-control" value="0">
            </div>
             <div class="form-group">
            	<label for="monto_apertura_caja">Monedas de 100 Gs</label>
            	<input type="number" name="moneda_100" class="form-control" value="0">
            </div>
             <div class="form-group">
            	<label for="monto_apertura_caja">Monedas de 50 Gs</label>
            	<input type="number" name="moneda_50" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
			{!!Form::close()!!}		
            
		</div>
	</div>
	@push ('scripts')
<script>


$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush
@endsection
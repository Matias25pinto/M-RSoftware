@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Cierre de Caja</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

		{!!Form::model($caja,['method'=>'PATCH','route'=>['ventas.caja.update',$caja->idcaja]])!!}
            {{Form::token()}}
            @foreach($ventas as $ven)
                  <input type="hidden" name="ventas" value="{{$ven->ventas}}">
            @endforeach
            <div class="form-group">
            	<h4>{{ Auth::user()->name }}</h4>
            </div>
            <label>CANTIDAD DE:</label>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 100.000 Gs</label>
                  <input type="number" id="100mil" name="100mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 50.000 Gs</label>
                  <input type="number" id="50mil" name="50mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 20.000 Gs</label>
                  <input type="number" id="20mil" name="20mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 10.000 Gs</label>
                  <input type="number" id="10mil" name="10mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 5000 Gs</label>
                  <input type="number" id="5mil" name="5mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Billetes de 2000 Gs</label>
                  <input type="number" id="2mil" name="2mil" class="form-control" value="0">
            </div>
            <div class="form-group">
                  <label for="monto_apertura_caja">Monedas de 1000 Gs</label>
                  <input type="number" id="moneda_1000" name="moneda_1000" class="form-control" value="0">
            </div>
             <div class="form-group">
                  <label for="monto_apertura_caja">Monedas de 500 Gs</label>
                  <input type="number" id="moneda_500" name="moneda_500" class="form-control" value="0">
            </div>
             <div class="form-group">
                  <label for="monto_apertura_caja">Monedas de 100 Gs</label>
                  <input type="number" id="moneda_100" name="moneda_100" class="form-control" value="0">
            </div>
             <div class="form-group">
                  <label for="monto_apertura_caja">Monedas de 50 Gs</label>
                  <input type="number" id="moneda_50" name="moneda_50" class="form-control" value="0">
            </div>
            <div class="form-group">
            	<button id="guardar" class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>

			{!!Form::close()!!}		
            
		</div>
	</div>
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active"); 
$('#liCajas').addClass("active");
</script>
@endpush
@endsection
@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Clientes</h3>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<a href="{{url('actualizarclientes/0')}}" target="_blank"><button class="btn btn-warning">Act. RUC0</button></a>
			<a href="{{url('actualizarclientes/1')}}" target="_blank"><button class="btn btn-warning">Act. RUC1</button></a>
			<a href="{{url('actualizarclientes/2')}}" target="_blank"><button class="btn btn-warning">Act. RUC2</button></a>
			<a href="{{url('actualizarclientes/3')}}" target="_blank"><button class="btn btn-warning">Act. RUC3</button></a>
			<a href="{{url('actualizarclientes/4')}}" target="_blank"><button class="btn btn-warning">Act. RUC4</button></a>
			<a href="{{url('actualizarclientes/5')}}" target="_blank"><button class="btn btn-warning">Act. RUC5</button></a>
			<a href="{{url('actualizarclientes/6')}}" target="_blank"><button class="btn btn-warning">Act. RUC6</button></a>
			<a href="{{url('actualizarclientes/7')}}" target="_blank"><button class="btn btn-warning">Act. RUC7</button></a>
			<a href="{{url('actualizarclientes/8')}}" target="_blank"><button class="btn btn-warning">Act. RUC8</button></a>
			<a href="{{url('actualizarclientes/9')}}" target="_blank"><button class="btn btn-warning">Act. RUC9</button></a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Proveedores</h3>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<a href="{{url('actualizarproveedores/0')}}" target="_blank"><button class="btn btn-warning">Act. RUC0</button></a>
			<a href="{{url('actualizarproveedores/1')}}" target="_blank"><button class="btn btn-warning">Act. RUC1</button></a>
			<a href="{{url('actualizarproveedores/2')}}" target="_blank"><button class="btn btn-warning">Act. RUC2</button></a>
			<a href="{{url('actualizarproveedores/3')}}" target="_blank"><button class="btn btn-warning">Act. RUC3</button></a>
			<a href="{{url('actualizarproveedores/4')}}" target="_blank"><button class="btn btn-warning">Act. RUC4</button></a>
			<a href="{{url('actualizarproveedores/5')}}" target="_blank"><button class="btn btn-warning">Act. RUC5</button></a>
			<a href="{{url('actualizarproveedores/6')}}" target="_blank"><button class="btn btn-warning">Act. RUC6</button></a>
			<a href="{{url('actualizarproveedores/7')}}" target="_blank"><button class="btn btn-warning">Act. RUC7</button></a>
			<a href="{{url('actualizarproveedores/8')}}" target="_blank"><button class="btn btn-warning">Act. RUC8</button></a>
			<a href="{{url('actualizarproveedores/9')}}" target="_blank"><button class="btn btn-warning">Act. RUC9</button></a>
		</div>
	</div>
</div>

@push ('scripts')
<script>
$('#licontabilidad').addClass("treeview active");
$('#liactualizarruc').addClass("active");
</script>
@endpush
@endsection
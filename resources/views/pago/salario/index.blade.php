@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
			<h3>Listado de pago de comisión por ventas<a  href="salario/create"><button id="nuevo" class="btn btn-success">Nuevo</button></a></h3>
			@include('pago.salario.search')
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed table-hover">
					<thead>
						<th>Nombre</th>
						<th>Monto cobrado</th>
                        <th>Comisión</th>
						<th>Estado</th>
						<th>Fecha de Pago </th>
                        <th>Opciones</th>
					</thead>
					@foreach ($pagos as $pa)
					<tr>
						<td>{{ $pa->nombre}}</td>
						<td>{{ number_format($pa->monto_pagado,0,'','.')}}</td>
                        <td>{{ $pa->comision."%"}}</td>
						<td>{{ $pa->estado}}</td>
						<td>{{ $pa->fecha_pago}}</td>
						<td>
                            <a href="{{URL::action('PagoController@detalles',$pa->idpago)}}"><button class="btn btn-info">Reporte</button></a>
							<a href="" data-target="#modal-delete-{{$pa->idpago}}" data-toggle="modal"><button class="btn btn-danger">Anular</button></a>
					</tr>
					@include('pago.salario.modal')
					@endforeach
				</table>
			</div>
			{{$pagos->render()}}
		</div>
	</div>
<input type="hidden" name="permisos" id="permisos" value="{{ Auth::user()->permisos }}" > 
@push ('scripts')
 
<script>

  $(document).ready(function() {
  if ($("#permisos").val() == "administrador")
    {
        $("#nuevo").show();
    }
     if ($("#permisos").val() == "ventas")
    {
        $("#nuevo").hide();
       
    }
});

$('#lipagos').addClass("treeview active");
$('#pagocomision').addClass("active");
</script>
@endpush
@endsection
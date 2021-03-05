@extends ('layouts.admin')
@section ('contenido')

<div class="row">

	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
  
		<h3>Listado de Reporte <a  href="pdf/create"><button id="nuevo" class="btn btn-success">Nuevo Reporte</button></a>
		</h3>
		
	</div>
</div>
@push ('scripts')
<script>

$('#licontabilidad').addClass("treeview active");
$('#lireportes').addClass("active");
</script>
@endpush
@endsection
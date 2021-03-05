@extends ('layouts.admin')
@section ('contenido')
<?php
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        //obener el id y cargar en una variable
        $usuarioActual = $user->id;
        $ruta = 'pdf/'.$usuarioActual.'_reporteventasmensual.pdf';
?>
<iframe src="{{asset($ruta)}}" style="width:100%; height:500px;" frameborder="0"></iframe>
@push ('scripts')
<script>
$('#licontabilidad').addClass("treeview active");
$('#lirportespdf').addClass("active");
</script>
@endpush
@endsection
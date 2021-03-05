@extends ('layouts.admin')
@section ('contenido')
<?php
        // Obtiene el objeto del Usuario Autenticado
        $user = Auth::user();
        //obener el id y cargar en una variable
        $usuarioActual = $user->id;
        $ruta = 'factura/'.$usuarioActual.'_factura.pdf';
?>
<h2> <a  href="{{URL::action('VentaController@create')}}"><button class="btn btn-success">Nueva Venta</button></a></h2>
<iframe src="{{asset($ruta)}}" style="width:100%; height:500px;" frameborder="0"></iframe>
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush
@endsection
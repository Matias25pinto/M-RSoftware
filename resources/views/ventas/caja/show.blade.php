@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="panel-body">
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Nombre Usuario:</label>
                    <p>{{$usuario->name}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="apertura">Apertura</label>
                    <p>{{$datos->fecha_hora_apertura}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                     <label for="apertura">Cierre</label>
                    <p>{{$datos->fecha_hora_cierre}}</p>
                </div>
            </div>
        </div>    
    </div>
    <div class="row">
        <div class="panel-body">
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Monto Apertura: </label>
                    <p>{{number_format($datos->monto_apertura_caja,0,'','.')}} Gs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Monto Cierre: </label>
                    <p>{{number_format($datos->monto_cierre_caja,0,'','.')}} Gs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Total Ingreso:</label>
                    <p>{{number_format($datos->ventas,0,'','.')}} Gs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Ventas + Monto Apertura:</label>
                    <p>{{number_format($datos->ventas + $datos->monto_apertura_caja,0,'','.')}} Gs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Diferencia:</label>
                    @IF($datos->monto_cierre_caja > ($datos->ventas + $datos->monto_apertura_caja))
                    <p>{{number_format($datos->monto_cierre_caja - ($datos->ventas + $datos->monto_apertura_caja),0,'','.')}} Gs.</p>
                    @ELSE
                    <p>{{number_format((($datos->ventas + $datos->monto_apertura_caja)-$datos->monto_cierre_caja),0,'','.')}} Gs.</p>
                    @ENDIF
               </div>
            </div>
        </div>
    </div>  
@push ('scripts')

<script>
$('#liVentas').addClass("treeview active");
$('#liCajas').addClass("active");
</script>
@endpush
@endsection
@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
    <div class="panel-body">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">N° de {{$venta->tipo_comprobante}}</label>
              <p>{{$comprobante->comprobante}}</p>
          </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">Fecha</label>
              <p>{{$comprobante->fecha_hora}}</p>
          </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">N° de Venta</label>
              <p>{{$comprobante->idventa}}</p>
          </div>
      </div>
    </div>    
    </div>
    <div class="row">
    <div class="panel-body">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">N° de {{$venta->tipo_documento}}</label>
              <p>{{$venta->num_documento}}</p>
          </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">Cliente</label>
              <p>{{$venta->nombre}}</p>
          </div>
      </div>
      
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">Dirección</label>
              <p>{{$venta->direccion}}</p>
          </div>
      </div>
    </div>
    </div>

    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">            

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                            
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Impuesto</th>
                            <th>Subtotal</th>
                        </thead>
                        <tfoot>
                            <tr>
                                  <th colspan="4"><p align="right">TOTAL IMPUESTO (10%): </p></th>
                                  <th><p align="right">Gs/. {{number_format((($impuesto10_unidad->precio_venta + $impuesto10_gramos->precio_venta)/11),0,'','.')}}</p></th>
                            </tr>
                            <tr>
                                  <th colspan="4"><p align="right">TOTAL IMPUESTO (5%): </p></th>
                                  <th><p align="right">Gs/. {{number_format((($impuesto5_unidad->precio_venta + $impuesto5_gramos->precio_venta)/21),0,'','.')}}</p></th>
                            </tr>
                             <tr>
                                 <tr>
                                  <th colspan="4"><p align="right">TOTAL EXENTO: </p></th>
                                  <th><p align="right">Gs/. {{number_format((($impuesto0_unidad->precio_venta + $impuesto0_gramos->precio_venta)),0,'','.')}}</p></th>
                            </tr>
                            </tr>
                            <tr>
                                <th  colspan="4"><p align="right">TOTAL PAGAR:</p></th>
                                <th><p align="right">Gs/. {{number_format(($venta->total_venta),0,'','.')}}</p></th>
                            </tr> 
                        </tfoot>
                        <tbody>
                            @foreach($detalles as $det)
                            <tr>
                                <td>{{$det->articulo}}</td>
                                <td>{{$det->cantidad}}</td>
                                <td>Gs/. {{number_format(($det->precio_venta),0,'','.')}}</td>
                                <td>{{$det->impuesto}}%</td>
                                @if($det->unidad_medida == "gramos")
                                  <td align="right">Gs/. {{number_format((($det->cantidad*$det->precio_venta-$det->descuento)/1000),0,'','.')}}</td>
                                @else
                                  <td align="right">Gs/. {{number_format(($det->cantidad*$det->precio_venta-$det->descuento),0,'','.')}}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    	
    </div>   
@push ('scripts')
<script>
$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
</script>
@endpush
@endsection
@extends ('layouts.admin')
  <style type="text/css">
   
   .ticket {
  font-size: 12px;
  text-align: center;
 
}
 
td,
th,
tr,
table {
  border-top: 1px solid black;
  border-collapse: collapse;
  text-align: center;
}
.tituloproductos{
  font-size: 12px;
  text-align: center;
}
.productos{
  font-size: 12px;
}
td.producto,
th.producto {
  width: 60px;
  max-width: 60px;
}
 
td.cantidad,
th.cantidad {
  width: 30px;
  max-width: 30px;
  word-break: break-all;
  
}
 
td.precio,
th.precio {
  width: 50px;
  max-width: 50px;
  word-break: break-all;
}
td.subtotal,
th.subtotal {
  width: 50px;
  max-width: 50px;
}
 
.centrado {
  text-align: center;
  align-content: center;
}
 
.ticket {
  width: 250px;
  max-width: 250px;
  max-height: 120%;
}
 
img {
  max-width: inherit;
  width: inherit;
}
@media print{ 
  .oculto-impresion, .oculto-impresion *{
    display: none !important;
  }
  .box {
  border-top: none !important;
  }
}
 </style>
@section ('contenido')
  <div class="ticket">
 
  <p class="tituloproductos">N° {{$comprobante->idventa}}</p>
      <p class="centrado">STYLO MUJER</p>
      <p class="centrado">Peluqueria & Centro de Estetica</p>
      <p class="centrado">www.stylomujerpeluqueria.com</p>
      <p class="centrado tituloproductos">{{ $vendedor->nombre }}</p>
      <p class="centrado tituloproductos">{{ $venta->fecha_hora }}</p>
    <table>
      <thead>
        <tr class="tituloproductos">
          <th class="cantidad"> Cant </th>
           
          <th class="producto"> Producto </th>
          <th class="precio"> Precio</th>
          <th class="subtotal"> SubTotal</th>
        </tr>
      </thead>
      <tbody>
    @foreach($detalles as $det)
        <tr class="productos">
          <td class="cantidad">{{ $det->cantidad}}</td>
          
          <td class="producto">{{$det->articulo}}</td>
          <td class="precio">{{number_format(($det->precio_venta),0,'','.')}}</td>
           @if($det->unidad_medida == "gramos")
              <td class="subtotal"> {{number_format((($det->cantidad*$det->precio_venta-$det->descuento)/1000),0,'','.')}}</td>
            @else
              <td class="subtotal"> {{number_format(($det->cantidad*$det->precio_venta-$det->descuento),0,'','.')}}</td>
            @endif
        </tr>
  @endforeach
         <tr class="productos">
         <td class="precio">TOTAL: </td>
         <td >{{number_format(($venta->total_venta),0,'','.')}}</td>
         </tr>
      </tbody>
    </table>
    <p class="centrado">¡GRACIAS POR SU PREFERENCIA!</p>
    <p class="centrado">www.stylomujerpeluqueria.com</p>
    <p class="centrado">No valido para credito fiscal</p>
    <p class="centrado">Uso interno</p>
    </div>
    <div class="oculto-impresion">
      <h3 class="oculto-impresion">VUELTO: {{number_format(($venta->importe - $venta->total_venta),0,'','.')}} Gs.</h3>
    </div>
    <a class="oculto-impresion"  href="{{URL::action('VentaController@create')}}"><button class="oculto-impresion btn btn-success" id="nuevo">Nueva Venta</button></a>
    <button class="oculto-impresion btn btn-info" onclick="imprimir()">Imprimir</button>
    <input type="hidden" name="apertura_caja" id="apertura_caja" value="{{ Auth::user()->apertura_caja }}" >
@push ('scripts')
<script>

    function imprimir() {


      window.print();

  }
window.onload = function(){

    window.print();
    evaluar();
}

function evaluar()
  
  {
    if ($("#apertura_caja").val() == 1)
    {
       
        $("#nuevo").show();
        
    }
    if ($("#apertura_caja").val() == 0)
    {
      
      $("#nuevo").hide();
      
    }
   }
</script>
@endpush
@endsection
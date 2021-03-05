@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Nueva Venta</h3>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
			{!!Form::model($venta,['method'=>'PATCH','route'=>['ventas.venta.update',$venta->idventa],'files'=>'true'])!!}
            {{Form::token()}}
     <div class="row">
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="form-group">
                <label for="cliente">Cliente</label>
                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}_{{$persona->num_documento}}">{{$persona->nombre}}</option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label>Tipo Comprobante</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                       <option value="Ticket">Ticket</option>
                       <option value="Factura">Factura</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
            <div class="form-group">
                <label for="pruc">N° de RUC/CI</label>
                <input type="text" disabled name="pruc" id="pruc" class="form-control" placeholder="N° de RUC/CI...">
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-21 col-xs-12">
                    <div class="form-group">
                        <label for="importe">Importe</label>
                        <input type="number" name="importe" id="importe" class="form-control" 
                        placeholder="Importe">
                    </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
               
    </div>
			{!!Form::close()!!}		


@push ('scripts')
<script>
  window.onload =  function mostrarCliente()
  {
    datosCliente=document.getElementById('idcliente').value.split('_');
    $("#pruc").val(datosCliente[1]);    
  }
}
  $("#idcliente").change(mostrarCliente);
 
</script>
@endpush
@endsection
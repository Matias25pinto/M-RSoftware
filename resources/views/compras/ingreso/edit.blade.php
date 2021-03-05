@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Ingreso</h3>
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
			{!!Form::open(array('url'=>'compras/ingreso','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
            <input type="hidden" name="enviararticulos" value="1">
    <div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Proveedor</label>
            	<select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}">{{$persona->nombre}}</option>
                     @endforeach
                </select>
            </div>
    	</div>
      <div class="col-lg-4 col-sm-4 col-md-6 col-xs-12">
        <div class="form-group">
          <label>Tipo Comprobante</label>
          <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                       <option value="Factura">Factura</option>
                       <option value="Ticket">Ticket</option>
          </select>
        </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label>Impuesto</label>
                <select name="impuesto" class="form-control">
                    <option value="10">10%</option>
                    <option value="5">5%</option>
                    <option value="0">Exento</option>
                </select>
            </div>
        </div>
      <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">
            <div class="form-group">
                <label for="serie_comprobante">Serie Comprobante</label>
                <input type="text" name="serie_comprobante" value="{{old('serie_comprobante')}}" class="form-control" placeholder="Serie comprobante...">
            </div>
        </div>
        <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">
            <div class="form-group">
                <label for="num_comprobante">Número Comprobante</label>
                <input type="text" name="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Número comprobante...">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="form-group">
                   
                        <h3>Artículo: {{$articulo->nombre}}</h3>
                        <h3>Código: {{$articulo->codigo}}</h3>
                        <input type="hidden" name="pidarticulo" value="{{$articulo->idarticulo}}" id="pidarticulo">
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" 
                        placeholder="cantidad">
                    </div>
                </div> 
            </div>
        </div>
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
    		<div class="form-group">
            	<input name"_token" value="{{ csrf_token() }}" type="hidden"></input>
                <button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>   
			{!!Form::close()!!}		

@push ('scripts')
<script>
  
  $('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection
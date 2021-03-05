@extends ('layouts.admin')
@section ('contenido')
<!-- daterangepicker. -->
<link rel="stylesheet" href="{{asset('bootstrap-daterangepicker/daterangepicker.css')}}">
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
			{!!Form::open(array('url'=>'compras/ingreso','method'=>'POST'))!!}
            {{Form::token()}}
    <div class="row">
    	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Proveedor</label>
            	<select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}_{{ $persona->num_documento }}_{{ $persona->direccion }}_{{ $persona->telefono }}">{{$persona->nombre}}</option>
                     @endforeach
                </select>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label for="ruc">RUC:</label>
              <input class="form-control" type="text" disabled name="ruc" id="pruc">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label for="direccion">Dirección:</label>
              <input class="form-control" type="text" disabled name="pdireccion" id="pdireccion">
            </div>
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
              <label for="telefono">Teléfono:</label>
              <input class="form-control" type="text" disabled name="ptelefono" id="ptelefono">
            </div>
        </div>
        
    	</div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
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
                <label for="num_comprobante">Número de Factura</label>
                <input type="text" onkeypress="return validarnrof(event)" name="num_comprobante" id="num_comprobante" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Número Factura...">
            </div>
        </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="timbrado">Timbrado</label>
                <input type="text" name="timbrado" value="{{old('timbrado')}}" class="form-control" placeholder="Número Timbrado...">
            </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="descuento_total">Descuento Total</label>
                  <input value="0"  type="text" required id="price3" name="number"  placeholder="Descuento Total..." class="form-control">
                  <input type="hidden" name="descuento_total" id="pdescuento_total" class="form-control">
            </div>
      </div> 
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
        <div class="form-group">
          <label>IVA Descuento Total</label>
          <select name="iva_descuento" id="iva_descuento" class="form-control">
                       <option select value="ninguna">--</option>
                       <option value="0">Exentas</option>
                       <option value="5">IVA 5%</option>
                       <option value="10">IVA 10%</option>
          </select>
        </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cuotas">Cuotas</label>
                        <input value="0" type="number" name="cuotas" id="pcuotas" class="form-control" 
                        placeholder="Cuotas...">
                    </div>
                </div> 
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <label for="precio_compra">Fecha de Factura</label>
            <div class="form-group">
            <button type="button" class="btn btn-default pull-rigth" id="daterange-btn" >
                <span>
                  <i class="fa fa-calendar"></i>Fecha de factura
                </span>
                  <i class="fa fa-caret-down"></i>
              </button> 
              <input id="fechaInicio" type="hidden" name="fechaInicio" class="form-control" placeholder="Fecha Inicio...">
           
              <input id="fechaFin" type="hidden" name="fechaFin" class="form-control" placeholder="Fecha Fin...">
            </div>
      </div>
  </div>
    
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-body">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                    <div class="form-group">
                        <label>Artículo</label>
                        <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
                            @foreach($articulos as $articulo)
                            <option value="{{$articulo->idarticulo}}_{{$articulo->impuesto}}">{{$articulo->articulo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" name="pcantidad" id="pcantidad" class="form-control" 
                        placeholder="Cantidad...">
                    </div>
                </div> 
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="precio_compra">Precio Compra</label>
                        <input  type="text" required id="price" name="number"  placeholder="Precio Compra..." class="form-control">
                        <input type="hidden" name="precio_compra" id="pprecio_compra" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                      <label for="descuento">Descuento</label>
                      <input value="0"  type="text" required id="price2" name="number"  placeholder="Descuento..." class="form-control">
                      <input type="hidden" id="pdescuento" class="form-control">
                    </div>
                </div> 
                <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
                    <div class="form-group">
                        <label for="bonificacion">Bonificación</label>
                        <input value="0" type="number" name="pbonificacion" id="pbonificacion" class="form-control" 
                        placeholder="Bonificación...">
                    </div>
                </div> 
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="form-group">
                       <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                            <th>Opciones</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Descuento</th>
                            <th>Bonificación</th>
                            <th>Impuesto %</th>
                            <th>SubTotal</th>
                        </thead>
                        <tfoot>
                            <tr>
                                
                                <th  colspan="1"><p align="left">Total Exentas: <span align="left" id="total_exentas">Gs/. 0</span></p></th>
                                <input type="hidden" name="exentas" id="pexentas">

                                <th  colspan="1"><p align="left">Total 5% : <span align="left" id="total_impuesto5">Gs/. 0</span></p></th>
                                <input type="hidden" name="impuesto5" id="pimpuesto5">

                                <th  colspan="1"><p align="left">Total 10% : <span align="left" id="total_impuesto10">Gs/. 0</span></p></th>
                                <input type="hidden" name="impuesto10" id="pimpuesto10">

                                <th  colspan="3"><p align="right">TOTAL:</p></th>
                                <th><p align="right"><span id="total">Gs/. 0</span> </p></th>
                                <input type="hidden" name="total_ingreso" id="total_ingreso">
                            </tr>   
                        </tfoot>
                        <tbody>
                            
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
    	<div class="col-lg-6 col-sm-6 col-md-6 col-xs-12" id="guardar">
    		<div class="form-group">
            	<input name="_token" value="{{ csrf_token() }}" type="hidden">
                <button class="btn btn-primary" type="submit">Guardar</button>
            	<button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
    	</div>
    </div>   
			{!!Form::close()!!}		

@push ('scripts')
<!-- daterangepicker. -->
<script src="{{asset('moment/min/moment.min.js')}}"></script>
     <script src="{{asset('bootstrap-daterangepicker/daterangepicker.js')}}"></script>

<script>
  $(document).ready(function(){
    mostrarValores()
    $('#bt_add').click(function(){
      agregar();
    });
  });
   $("#idproveedor").change(mostrarValores);
  var cont=0;
  var total=0;
  var subtotal=[];
  var exentas = 0;
  var impuesto5 = 0;
  var impuesto10 = 0;

  $("#guardar").hide();
  
  
function mostrarValores()
  {
    datosProveedor=document.getElementById('idproveedor').value.split('_');
    $("#pruc").val(datosProveedor[1]); 
    $("#pdireccion").val(datosProveedor[2]); 
    $("#ptelefono").val(datosProveedor[3]); 
  }
  function agregar()
  {
    datosArticulo=document.getElementById('pidarticulo').value.split('_');

    impuesto=datosArticulo[1];
    idarticulo=$("#pidarticulo").val();
    articulo=$("#pidarticulo option:selected").text();
    cantidad=$("#pcantidad").val();
    bonificacion=$("#pbonificacion").val();
    precio_compra=$("#pprecio_compra").val();
    descuento=$("#pdescuento").val();
    subtotal = (precio_compra-descuento) * cantidad
    total = total + subtotal;
    
    if (idarticulo!="" && cantidad!="" && cantidad>0 && bonificacion!="")
    {
      if(impuesto == 0){
        exentas = exentas + subtotal;
      }
      if(impuesto == 5){
        impuesto5 = impuesto5 + subtotal;
      }
      if(impuesto == 10){
        impuesto10 = impuesto10 + subtotal;
      }

        var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><label>'+parseFloat(cantidad).toFixed(0)+'</label></td><input type="hidden" name="cantidad[]" value="'+parseFloat(cantidad).toFixed(0)+'"><td><label>'+parseFloat(precio_compra).toFixed(0)+'</label></td><input type="hidden" name="precio_compra[]" value="'+parseFloat(precio_compra).toFixed(0)+'"><td><label>'+parseFloat(descuento).toFixed(0)+'</label></td><input type="hidden" name="descuento[]" value="'+parseFloat(descuento).toFixed(0)+'"><td><label>'+parseFloat(bonificacion).toFixed(0)+'</label></td><input type="hidden" name="bonificacion[]" value="'+parseFloat(bonificacion).toFixed(0)+'"><td><label>'+parseFloat(impuesto).toFixed(0)+'</label></td><input type="hidden" name="impuesto[]" value="'+parseFloat(impuesto).toFixed(0)+'"><td><label>'+parseFloat(subtotal).toFixed(0)+'</label></td><input type="hidden" name="subtotal[]" value="'+parseFloat(subtotal).toFixed(0)+'"></tr>';
        cont++;

        totales();
        limpiar();
        evaluar();
        $('#detalles').append(fila);
    }
    else
    {
        alert("Error al ingresar el detalle del ingreso, revise los datos del artículo");
    }
  }
  function limpiar(){
    $("#pcantidad").val("");
    $("#pbonificacion").val(0);
    $("#pprecio_compra").val("");
    $("#price2").val(0);
    $("#pdescuento").val(0);
    $("#price").val("");
  }
  function totales(){

        
        $("#total").html("Gs/ "+$.number(total));
        $("#total_ingreso").val(total.toFixed(0));

        $("#total_exentas").html("Gs/ "+$.number(exentas));
        $("#pexentas").val(exentas.toFixed(0));

        $("#total_impuesto5").html("Gs/ "+$.number(impuesto5));
        $("#pimpuesto5").val(impuesto5.toFixed(0));

        $("#total_impuesto10").html("Gs/ "+$.number(impuesto10));
        $("#pimpuesto10").val(impuesto10.toFixed(0));
        
        total_pagar=total;
       
       
    }
  function evaluar()
  {
    if (cantidad>0)
    {
      $("#guardar").show();
    }
    else
    {
      $("#guardar").hide(); 
    }
   }

   function eliminar(index){ 
    $("#fila" + index).remove();
    evaluar();

  }
   //formatear precio compra
  $(function(){
                // Set up the number formatting.
                $('#price').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price').val();
                    
                    $('#pprecio_compra').val(val);
                    
                });
                
                $('#price').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price').val();
                    
                    $('#pprecio_compra').val(val);
                });

                
            });
            //formatear descuento
            $(function(){
                // Set up the number formatting.
                $('#price2').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price2').val();
                    
                    $('#pdescuento').val(val);
                    
                });
                
                $('#price2').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price2').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price2').val();
                    
                    $('#pdescuento').val(val);
                });

                
            });
            //formatear descuento total
            $(function(){
                // Set up the number formatting.
                $('#price3').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price3').val();
                    
                    $('#pdescuento_total').val(val);
                    
                });
                
                $('#price3').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price3').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price3').val();
                    
                    $('#pdescuento_total').val(val);
                });

                
            });
    //formatear nro factura, evita que se escriba un valor que no sea numero o guion
    function validarnrof(e){
              var val1 = $('#num_comprobante').val();
              
              tecla = (document.all) ? e.keyCode : e.which;
              tecla = String.fromCharCode(tecla)
              if(val1.length == 3 || val1.length == 7){
                
                var val = $('#num_comprobante').val();
            
                $('#num_comprobante').val(val.concat('-'));
                
            }
              return /^[0-9\-]+$/.test(tecla);
      }
 //Rango de fechas
    

 $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          
          
          
        },
         "locale": {
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ],
    },
        startDate: moment(),
        endDate  : moment()
      },

      function (start, start) {

        $('#daterange-btn span').html(start.format('DD-MM-YYYY'));
        var fechaInicial = start.format('YYYY-MM-DD');
        var fechaFinal = start.format('YYYY-MM-DD');
        var capturarRango = $("#daterange-btn span").html;

        $("#fechaInicio").val(fechaInicial);
        $("#fechaFin").val(fechaFinal);
        document.getElementById("guardar").click();
        
      }
    )
     //Cancelar rango de fecha
     $(".daterangepicker .range_inputs .cancelBtn").on("click", function(){
        localStorage.removeItem("capturarRango");
        localStorage.clear();
        window.location = "";
     })
     //Capturar hoy
     $(".daterangepicker .ranges li").on("click", function(event){
      var textoHoy = $(this).attr("data-range-key");
      if (textoHoy == "Hoy") {
        var d =new Date();
        var dia = d.getDate();
        var mes = d.getMonth()+1;
        var año = d.getFullYear();

        if (mes < 10 && dia > 10) {

          var fechaInicial = año+"-0"+mes+"-"+dia;

          var fechaFinal = año+"-0"+mes+"-"+dia;

        }else if (dia < 10 && mes > 10) {

          var fechaInicial = año+"-"+mes+"-0"+dia;

          var fechaFinal = año+"-"+mes+"-0"+dia;

        }else if (mes < 10 && dia < 10) {

          var fechaInicial =  año+"-0"+mes+"-0"+dia;

          var fechaFinal =  año+"-0"+mes+"-0"+dia;

        }else{

          var fechaInicial = año+"-"+mes+"-"+dia;

          var fechaFinal = año+"-"+mes+"-"+dia;
        }

        
        localStorage.setItem("capturaRango", "Hoy");

        $("#fechaInicio").val(fechaInicial);
        $("#fechaFin").val(fechaFinal);
        document.getElementById("guardar").click();
      }
     })
$('#liCompras').addClass("treeview active");
$('#liIngresos').addClass("active");
</script>
@endpush
@endsection
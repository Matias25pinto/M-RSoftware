@extends ('layouts.admin')
@section ('contenido')
<!-- daterangepicker. -->
<link rel="stylesheet" href="{{asset('bootstrap-daterangepicker/daterangepicker.css')}}">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Cargar Factura de venta o Nota de Credito Compra</h3>
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
  {!!Form::open(array('url'=>'cargar/ventas','method'=>'POST','autocomplete'=>'off','files'=>'true'))!!}
            {{Form::token()}}
    <div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
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
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
    		<div class="form-group">
            	<label for="proveedor">Cliente</label>
            	<select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}_{{ $persona->num_documento }}_{{ $persona->direccion }}_{{ $persona->telefono }}">{{$persona->nombre}} - {{$persona->num_documento}}</option>
                     @endforeach
                </select>
                <input id="cliente" type="hidden" name="cliente" class="form-control">
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
                       <option value="Factura">Factura de venta</option>
                       <option value="NotaCredito-compras">Nota Credito Compra</option>
          </select>
        </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="nro_factura">Número de Factura</label>
                <input  type="text" onkeypress="return validarnrof(event)" name="nro_factura" id="nro_factura" required value="{{old('num_comprobante')}}" class="form-control" placeholder="Número Factura...">
            </div>
        </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
            <div class="form-group">
                <label for="timbrado">Timbrado</label>
                <input onkeypress="return event.keyCode!=13" type="text" name="timbrado" id="timbrado" value="{{old('timbrado')}}" class="form-control" placeholder="Número Timbrado...">
            </div>
      </div>
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="exentas">Subtotal Exentas</label>
                  <input onkeypress="return event.keyCode!=13" value="0"  type="text" required id="price1" name="number"  placeholder="Subtotal Exentas..." class="form-control">
                  <input type="hidden" name="exentas" id="exentas" class="form-control">
            </div>
      </div> 

      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="impuesto5">Subtotal Impuesto 5%</label>
                  <input onkeypress="return event.keyCode!=13" value="0"  type="text" required id="price2" name="number"  placeholder="Subtotal Impuesto 5%..." class="form-control">
                  <input type="hidden" name="impuesto5" id="impuesto5" class="form-control">
                  
            </div>
      </div> 

      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="iva5">IVA 5%</label>
                  <input disabled value="0"  type="text" required id="iva5" name="number"  placeholder="IVA 5%..." class="form-control">
            </div>
      </div> 

      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="impuesto10">Subtotal Impuesto 10%</label>
                  <input onkeydown="calculariva5()" onkeypress="return event.keyCode!=13" value="0"  type="text" required id="price3" name="number"  placeholder="Subtotal Impuesto 10%..." class="form-control">
                  <input type="hidden" name="impuesto10" id="impuesto10" class="form-control">
            </div>
      </div> 
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="iva10">IVA 10%</label>
                  <input disabled value="0"  type="text" required id="iva10" name="number"  placeholder="IVA 10%..." class="form-control">
            </div>
      </div> 

      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
            <div class="form-group">
                  <label for="iva5">Total </label>
                  <input disabled value="0"  type="text" required id="total" name="number"  placeholder="total..." class="form-control">
            </div>
      </div> 

      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" id="guardar">
            <div class="form-group">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <button id="guardar" class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
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
        mostrarValores();
        evaluar();
  });

 
   $("#idcliente").change(mostrarValores);
   //mostrar iva 5%
           $(function(){
            try{
                  $('#price2').on('keyup', function(){
                      var val = $('#price2').val();
                      var iva5 = Math.round(val/21);
                      $('#iva5').val(iva5);
                      var total = parseInt($('#price1').val()) + parseInt($('#price2').val()) + parseInt($('#price3').val());
                      $('#total').val(total);
                  }).keyup();
                }catch(e){}});

            //mostrar iva 10%
            $(function(){
            try{
                  $('#price3').on('keyup', function(){
                      var val = $('#price3').val();
                      var iva10 = Math.round(val/11);
                      $('#iva10').val(iva10);
                      var total = parseInt($('#price1').val()) + parseInt($('#price2').val()) + parseInt($('#price3').val());
                      $('#total').val(total);
                  }).keyup();
                }catch(e){}});
  

  $("#guardar").hide();
 
  function evaluar(){    
        if (1){
            $("#guardar").show();
        }
        else{
            $("#guardar").hide(); 
        }
    }
function mostrarValores()
  {
    datosProveedor=document.getElementById('idcliente').value.split('_');
    $("#cliente").val(datosProveedor[0]); 
    $("#pruc").val(datosProveedor[1]); 
    $("#pdireccion").val(datosProveedor[2]); 
    $("#ptelefono").val(datosProveedor[3]); 
    
  }
 
   //formatear subtotal exentas
  $(function(){
                // Set up the number formatting.
                $('#price1').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price1').val();
                    
                    $('#exentas').val(val);
                    
                });
                
                $('#price1').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price1').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price1').val();
                    
                    $('#exentas').val(val);
                });

                
            });
            //formatear subtotal 5%
            $(function(){
                // Set up the number formatting.
                $('#price2').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price2').val();
                    $('#impuesto5').val(val);
                    
                });
                
                $('#price2').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price2').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price2').val();
                    
                    $('#impuesto5').val(val);
                });

                
            });
            

            
            //formatear subtotal 10%
            $(function(){
                // Set up the number formatting.
                $('#price3').on('change',function(){
                    console.log('Change event.');
                    var val = $('#price3').val();
                    
                    $('#impuesto10').val(val);
                    
                });
                
                $('#price3').change(function(){
                    console.log('Second change event...');
                });
                
                $('#price3').number( true, 0 );
                
                
                // Get the value of the number for the demo.
                $('#guardar').on('click',function(){
                    
                    var val = $('#price3').val();
                    
                    $('#impuesto10').val(val);
                });

                
            });
            //formatear Total a pagar 
            $(function(){
                // Set up the number formatting.
                $('#total').on('change',function(){
                    console.log('Change event.');
                    var val = $('#total').val();
                    
                    
                });
                
                $('#total').change(function(){
                    console.log('Second change event...');
                });
                
                $('#total').number( true, 0 );

                
            });
    //formatear nro factura, evita que se escriba un valor que no sea numero o guion
    function validarnrof(e){
              var val1 = $('#nro_factura').val();
              
              tecla = (document.all) ? e.keyCode : e.which;
              tecla = String.fromCharCode(tecla)
              if(val1.length == 3 || val1.length == 7){
                
                var val = $('#nro_factura').val();
            
                $('#nro_factura').val(val.concat('-'));
                
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

            

$('#licontabilidad').addClass("treeview active");
$('#lifacturasventas').addClass("active");
</script>
@endpush
@endsection
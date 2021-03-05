@extends ('layouts.admin')
@section ('contenido')
<!-- daterangepicker. -->
    <link rel="stylesheet" href="{{asset('bootstrap-daterangepicker/daterangepicker.css')}}">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Reporte de Ventas por Rango</h3>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif
    
    {!!Form::open(array('url'=>'reportes/pdf','method'=>'POST'))!!}
			{!!Form::token()!!}
            <div class="form-group">
                <label>Tipo de Reporte</label>
                <select name="tiporeporte" class="form-control">
                    <option value="ventas" >Reporte Ventas</option>
                    <option value="facturas" >Reporte Facturas</option>
                    <option value="compras" >Reporte Compras</option>
                   
                </select>
            </div>
            <div class="form-group">
            
                <input id="fechaInicio" type="hidden" name="fechaInicio" class="form-control" placeholder="Fecha Inicio...">
           
                <input id="fechaFin" type="hidden" name="fechaFin" class="form-control" placeholder="Fecha Fin...">
            </div>

            <button type="button" class="btn btn-default pull-rigth" id="daterange-btn" >
              <span>
                <i class="fa fa-calendar"></i>Rango de fecha
              </span>
               <i class="fa fa-caret-down"></i>
            </button>
            <hr>
            <div class="form-group">
                <button id="guardar" class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>

      {!!Form::close()!!}     
            
        </div>
    </div>
    
@push ('scripts')
 <!-- daterangepicker. -->
     <script src="{{asset('moment/min/moment.min.js')}}"></script>
     <script src="{{asset('bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script>


 //Rango de fechas
    

    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          
          'Hoy'       : [moment(), moment()],
          'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
          'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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

      function (start, end) {

        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var fechaInicial = start.format('YYYY-MM-DD');
        var fechaFinal = end.format('YYYY-MM-DD');
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
$('#lirportespdf').addClass("active");
</script>
@endpush
@endsection
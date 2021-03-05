@extends ('layouts.admin')
@section ('contenido')
<!-- daterangepicker. -->
    <link rel="stylesheet" href="{{asset('bootstrap-daterangepicker/daterangepicker.css')}}">
    <div class="row">
    @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif
    {!!Form::open(array('url'=>'reportes/venta','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3>Reporte para Hechauka</h3>
            <div class="form-group">
                <label>Formato</label>
                <select name="formato" class="form-control">
                    <option value="xls" >Excel</option>
                    <option value="txt" >Txt</option>
                   
                    
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de Reporte</label>
                <select name="reporte" class="form-control">
                    <option value="ventas" >Reporte Ventas</option>
                    <option value="compras" >Reporte Compras</option>
                    <option value="resumen" >Resumen General</option>
                   
                </select>
            </div>
            
    <button type="button" class="btn btn-default pull-rigth" id="daterange-btn" >
      <span>
        <i class="fa fa-calendar"></i>Rango de fecha
      </span>
      <i class="fa fa-caret-down"></i>
    </button>
           
            <div class="form-group">
            
                <input id="fechaInicio" type="hidden" name="fechaInicio" class="form-control" placeholder="Fecha Inicio...">
           
                <input id="fechaFin" type="hidden" name="fechaFin" class="form-control" placeholder="Fecha Fin...">
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
            
        </div>
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
$('#lireportes').addClass("active");
</script>
@endpush
@endsection
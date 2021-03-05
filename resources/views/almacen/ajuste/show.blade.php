@extends ('layouts.admin')
@section ('contenido')
<div class="row">
<div class="panel-body">
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">Ajuste N° : {{$ajuste->idajuste}}</label>
              
          </div>
      </div>
      <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
          <div class="form-group">
              <label for="nombre">Fecha de ajuste</label>
              <p>{{$ajuste->fecha_hora}}</p>
          </div>
      </div>
</div>
</div>
<div class="row">
<div class="panel-body">
      <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
          <div class="form-group">
              <label for="nombre">Detalle de Ajuste</label>
              <p>{{$ajuste->detalle}}</p>
          </div>
      </div>
      
</div>
</div>

<div class="row">
      <div class="panel panel-primary">
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
      <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color: #A9D0F5">
            
                        <th>Articulo</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                        <th>Stock</th>
                        
                    </thead>
            <tfoot>
              
                <th></th>
                <th></th>
                <th></th>
                
            </tfoot>
            <tbody> 
                <tr>
                  <td>{{$ajuste->nombre}}</td>
                  <td>{{$ajuste->cantidad}}</td>
                  <td>{{$ajuste->estado}}</td>
                  <td>{{$ajuste->stock}}</td>
                </tr>
            </tbody>
      </table>
            </div>
          </div>
        </div>
      </div>       
</div>
   
         @endsection﻿
@extends ('layouts.admin')
@section ('contenido')
<h2>{{$tipo.' Actualizados en base de la set: con terminación de ruc '.$nro_ruc}}</h2>
<?php
//NO ES LA MEJOR FORMA DE HACER
use sisMR\Persona;
use sisMR\Base_set;
    //ESTE CODIGO NOS PERMITES PROCESAR SCRIPT MUY PESADOS AUMENTANDO EL TIEMPO DE EJECUCION
    ini_set('max_execution_time', 1800); //1800 seconds = 30 minutes
    //variable de control de actualizacion
    $actualizo = 0;  
    $contador = 0;
    $cant_clientes = 0;
    $lista = [];
    //Lee el archivo de la carpeta public
    $prueba = fopen(public_path().'/ruc/ruc'.$nro_ruc.'.txt','r') or die ('Error de lectura');
    
        while(!feof($prueba)){
            
            //Lee linea por linea el contenido del archivo
            $line = fgets($prueba);

            $saltodelinea=nl2br($line);

            $row[] = $saltodelinea;
            //separa la primera colummna
            $token1 = strtok($row[$contador], "|");
            //separa la segunda columma
            $token2 = strtok("|");

            $token3 = strtok("|");
            
            $cedula = $token1.'-'.$token3;
            if($actualizo == 0){
                $cant_clientes  = $cant_clientes + 1;
                $base = new Base_set;
                $base->nombre = $token2;
                $base->num_documento = $cedula;
                $base->save();
                echo $cant_clientes.' - '.$base->num_documento.' '.$base->nombre.'<br>';
            }
           
            
            $contador = $contador + 1;
        }

        fclose($prueba);      
?>
@push ('scripts')
<script>
$('#licontabilidad').addClass("treeview active");
$('#liactualizarruc').addClass("active");
</script>
@endpush
@endsection
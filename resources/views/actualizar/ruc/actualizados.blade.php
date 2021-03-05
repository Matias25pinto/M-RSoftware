@extends ('layouts.admin')
@section ('contenido')
<h2>{{$tipo.' Actualizados: con terminación de ruc '.$nro_ruc}}</h2>
<?php
//NO ES LA MEJOR FORMA DE HACER
use sisMR\Persona;
    //Lee el archivo de la carpeta public
            
    $contador = 0;
    $cant_clientes = 0;
    $lista = [];
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
            foreach ($personas as $persona) {
                if($cedula  == $persona->num_documento){
                    $cant_clientes  = $cant_clientes + 1;
                   
                    $actualizar=Persona::findOrFail($persona->idpersona);

                    $actualizar->nombre=$token2;
            
                    $actualizar->update();
                       
                    echo $cant_clientes.' - '.$actualizar->num_documento.' '.$actualizar->nombre.'<br>';
                    
                }
                
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
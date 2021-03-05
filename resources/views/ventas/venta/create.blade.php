@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label>Nueva Venta</label>
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
            {!!Form::open(array('url'=>'ventas/venta','method'=>'POST','autocomplete'=>'off','onsubmit' => ' return validacion();'))!!}
            {!!csrf_field()!!}
            {!!Form::token()!!}
    <div class="row">
        <input type="hidden"  name="idusers"  class="form-control"  value="{{ Auth::user()->id}}">
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <div class="form-group">
                <label for="vendedor">Vendedor</label>
                <select name="idvendedor" id="idvendedor" class="form-control selectpicker" data-live-search="true">
                    @foreach($vendedores as $vendedor)
                     <option value="{{$vendedor->idpersona}}">{{$vendedor->nombre}} - {{ $vendedor->num_documento }}</option>
                     @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
            <div class="form-group">
                <label for="cliente">Cliente - CI/RUC</label>
                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">
                    @foreach($personas as $persona)
                     <option value="{{$persona->idpersona}}_{{$persona->num_documento}}">{{$persona->nombre}}/{{ $persona->num_documento }}</option>
                     @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <div class="form-group">
                <label>Tipo Comprobante</label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="form-control">
                       <option value="0">---</option>
                       <option value="Ticket" selected="true">Ticket</option>
                       <option value="Factura" >Factura</option>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <div class="form-group">
                <label for="pruc">Importe = <span id="total2">Gs/. 0</span></label>
                <input  type="text" required id="price" name="number" placeholder="Importe..." class="form-control">
                <input  type="hidden" name="importe" id="importe" >
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <div class="form-group">
                <label for="pruc"><span id="pcant_facturas">Cant. Facturas: </span></label>

            </div>
        </div>
        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
                <button type="button" id="bt_add" class="btn btn-primary">Agregar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <input type="text" name="pcantidad" id="pcantidad" class="form-control" placeholder="cantidad..." value="1">
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <div class="form-group">
                <label for="precio_venta">Pre. venta</label>
                <select name="precios_ventas" class="form-control" id="precios_ventas">
                    <option value="1">Automatico</option>
                    <option value="2">Unitario</option>
                    <option value="3">Mayorista</option>
                    <option value="4">Especial</option>
                    <option value="5">Precio Modificado</option>
                </select>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <label for="modificado">Precio Modificado</label>
            <input  type="text" disabled required id="pricemodificado" name="number" placeholder="Precio Modificado..." class="form-control">
             <input  type="hidden" name="modificado" id="modificado" class="form-control" placeholder="Precio Modificado..." value="">
        </div>
         <div class="col-lg-1 col-sm-1 col-md-1 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" disabled name="pstock" id="pstock" class="form-control" placeholder="Stock">
                <input type="hidden"  name="pimpuesto" id="pimpuesto" class="form-control">
            </div>
        </div>
        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">  
            <label for="item">Item</label>
            <input type="number" disabled name="pitem" id="pitem" class="form-control" placeholder="Cant...">
        </div>
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
             <div class="form-group">
                <label>Artículo</label>
                <input type="text" id="buscar" class="form-control">
                <select name="pidarticulo" class="form-control selectpicker" id="pidarticulo" data-live-search="true">
                    @foreach($articulos as $articulo)
                        <option value="{{$articulo->idarticulo}}_{{$articulo->stock}}_{{$articulo->precio_venta}}_{{$articulo->precio_venta2}}_{{$articulo->precio_venta3}}_{{$articulo->impuesto}}_{{$articulo->unidad_medida}}_{{$articulo->nombre}}">{{$articulo->articulo}}-{{$articulo->nombre}}</option>
                    @endforeach
                </select>
                
            </div>
    </div>    
                <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5">
                            <th>Opciones</th>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Impuesto %</th>
                            <th>Subtotal</th>
                        </thead>
                        <tfoot>
                            <tr>
                                <th  colspan="5"><p align ="right">TOTAL:</p></th>
                                <th><p align ="right"><span id="total">Gs/. 0</span> <input type="hidden" name="total_venta" id="total_venta"></p></th>
                            </tr>   
                        </tfoot>
                        <tbody> 
                        </tbody>
                    </table>
                 </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12" id="guardar">
            <div class="form-group">
                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                <button id="guardar" class="btn btn-success" type="submit">Cobrar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>   
    </div>
            {!!Form::close()!!}     

@push ('scripts')
<script>
    $(function(){

        // PRECIO compra
        $('#price').on('change',function(){
            console.log('Change event.');
            var val1 = $('#price').val();

            $('#importe').val(val1);

        });

        $('#price').change(function(){
            console.log('Second change event...');
        });

        $('#price').number( true, 0 );

        $('#guardar').on('click',function(){

            var val1 = $('#price').val();

            $('#importe').val(val1);

        });

       // PRECIO modificado
       $('#pricemodificado').on('change',function(){
            console.log('Change event.');
            var val1 = $('#pricemodificado').val();

            $('#modificado').val(val1);

        });

        $('#pricemodificado').change(function(){
            console.log('Second change event...');
        });

        $('#pricemodificado').number( true, 0 );

        $('#bt_add').on('click',function(){

            var val1 = $('#pricemodificado').val();

            $('#modificado').val(val1);

        });
        
    });  

    $(document).ready(function(){
        mostrarValores();
        $('#bt_add').click(function(){
            agregar();
        });
    });
    $('body').keyup(function(e) {
        
		if(e.which == 13){
            var cadena = $("#buscar").val();
            var accion = cadena.substring(0,1);
            var valor = cadena.substring(1);
            //cambiar cantidad
            if(accion.toUpperCase() == 'C'){
                if(/^([0-9])*$/.test(valor)){
                    $("#pcantidad").val(valor);
                    limpiar();
                }
                else{
                    alert('No es un número el valor ingresado');
                    limpiar();
                } 
            }
            //cambiar precio
            if(accion.toUpperCase() == 'P'){
                if(/^([0-9])*$/.test(valor+1) && valor<5){
                    document.getElementById('precios_ventas').selectedIndex = valor; 
                    limpiar();
                }
                else{
                    alert('No es valido el precio ingresado');
                    limpiar();
                } 
                if ( valor=== "4") {
                
                    $("#pricemodificado").prop("disabled", false);
                     document.getElementById('pricemodificado').focus();
                }          
                else {
                    $("#pricemodificado").val("")
                    $("#pricemodificado").prop("disabled", true);
                }
            }
            //cambiar tipo de comprobante
            if(accion.toUpperCase() == 'T'){
                if(/^([0-9])*$/.test(valor) && valor<3 && valor != 0){
                    if(valor == 1){
                        document.getElementById('tipo_comprobante').selectedIndex = valor; 
                        limpiar();
                    }
                    if(valor == 2){
                        document.getElementById('tipo_comprobante').selectedIndex = valor; 
                        limpiar();
                    }
                    
                }
                else{
                    alert('No es valido el Tipo de comprobante ingresado');
                    limpiar();
                } 
            }
            //importe
            if(accion.toUpperCase() == 'I'){
                if(/^([0-9])*$/.test(valor)){
                    $("#price").val(valor);
                    limpiar();
                }
                else{
                    alert('No es un número el valor ingresado');
                    limpiar();
                } 
            }
            //ingresar codigo de barra
            if(/^([0-9])*$/.test(cadena)){
                buscarSelect();    
            }
             
		}
	});
    var cont = 0;
    var cont_item = 0;
    var cant_facturas = 0;
    total=0;
    subtotal=[];
    $("#guardar").hide();
    $("#pidarticulo").change(mostrarValores);
    $("#idcliente").change(mostrarCliente);
    $("#tipo_comprobante").change(marcarImpuesto);

    function buscarSelect(){
        
        var art_existe = 0;
        var art;
	    // creamos un variable que hace referencia al select
        var select=document.getElementById("pidarticulo");
 
        // obtenemos el valor a buscar
        var buscar=document.getElementById("buscar").value;

        // recorremos todos los valores del select
        for(var i=0;i<select.length;i++){
            select_texto = select.options[i].text;

            text_buscar = select_texto.split("-");

            if(text_buscar[0] == buscar){
                // seleccionamos el valor que coincide
                select.selectedIndex=i;
                mostrarValores()
                agregar();    
                art_existe = 1;
            }
        }
        if(art_existe == 0){
            limpiar();
            alert('No existe articulo');
        }
    }
    function mostrarValores()
  {
    datosArticulo=document.getElementById('pidarticulo').value.split('_');
    
    $("#vprecio_venta").html("Gs/ "+$.number(datosArticulo[2]));
    $("#pstock").val(datosArticulo[1]);
    datosCliente=document.getElementById('idcliente').value.split('_');
    $("#pruc").val(datosCliente[1]);  
    $unidad_medida = MaysPrimera(datosArticulo[6]);
    $("#punidad_medida").html($unidad_medida);  
  }
  function MaysPrimera(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
  function mostrarCliente()
  {
    datosCliente=document.getElementById('idcliente').value.split('_');
    $("#pruc").val(datosCliente[1]);    
  }
  

  function agregar(){
      //ESTO PERMITE GENERAR SOLO HASTA 105 ITEM
    if(cont_item < 105){
        
        datosArticulo=document.getElementById('pidarticulo').value.split('_');
        idarticulo=datosArticulo[0];
        unidad_medida=(datosArticulo[6]);
        impuesto=(datosArticulo[5]);
        articulo=(datosArticulo[7]);
        cantidad=$("#pcantidad").val();
        stock=$("#pstock").val();

        /* Para obtener el texto */
        var combo = document.getElementById("precios_ventas");
        var selected = combo.options[combo.selectedIndex].text;
        //cacular precio ventas
        if(selected === 'Unitario'){
            precio_venta=datosArticulo[2];
        }
        if(selected === 'Mayorista'){
            precio_venta=datosArticulo[3];
        }
        if(selected === 'Especial'){
            precio_venta=datosArticulo[4];
        }
        if(selected === 'Automatico'){
            if(cantidad < 3 || unidad_medida =='gramos'){
                precio_venta=datosArticulo[2];
            }
            else{
                precio_venta=datosArticulo[3];
            }
        }
        if(selected === 'Precio Modificado'){
            precio_venta = $('#modificado').val();
        }
    
        var control_duplicado = 0;
        var str1='#art_';
        var pos='#can_';
        var label = '#label_';
        var precioax1 = '#precio_';
        var precioax2 = '#preciolabel_';
        var subtotalax1 = '#subtotal_';
        var subtotalax2 = '#tdsubtotal_';
    
        for(var i=0;i<cont;i++){
            var res = str1.concat(i);
            var id_buscar = $(res).val();

            if(id_buscar == idarticulo){
                var indice_inicial = pos.concat(i);
                var indice_label = label.concat(i);
                idart = $(indice_inicial).val();
                var nueva_cantidad = parseInt(idart)+parseInt(cantidad);

                //cacular precio ventas
        if(selected === 'Unitario'){
            precio_venta=datosArticulo[2];
        }
        if(selected === 'Mayorista'){
            precio_venta=datosArticulo[3];
        }
        if(selected === 'Especial'){
            precio_venta=datosArticulo[4];
        }
        if(selected === 'Automatico'){
            if(nueva_cantidad < 3 || unidad_medida =='gramos'){
                precio_venta=datosArticulo[2];
            }
            else{
                precio_venta=datosArticulo[3];
            }
        }
        if(selected === 'Precio Modificado'){
            precio_venta = $('#modificado').val();
        }

                $(indice_inicial).val(nueva_cantidad);
                $(indice_label).html($(indice_inicial).val()); 

                
                var indice_precioax1 = precioax1.concat(i);
                var indice_precioax2 = precioax2.concat(i);
                
                $(indice_precioax1).val(parseInt(precio_venta));

                $(indice_precioax2).html($(indice_precioax1).val()); 

                //calcular subtotal si se repite
                total = total - subtotal[i];
                if ( unidad_medida == "gramos") {
                    precio_axu = precio_venta/1000;
                    subtotal[i]=(nueva_cantidad*precio_axu);
                }
                if ( unidad_medida == "unidad" || unidad_medida == "servicio"){
                    subtotal[i]=(nueva_cantidad*precio_venta);
                }
                   
                total=total+subtotal[i];

                var indice_precioax2 = subtotalax2.concat(i);
                $(indice_precioax2).html('Gs/. '+parseInt(subtotal[i])); 
                    

                control_duplicado = 1; 
                totales();
                limpiar();   
            }
        }  
            if(control_duplicado == 0){
            if (idarticulo!="" && cantidad!="" && cantidad>0 && precio_venta!=0){
                if (parseInt(stock)>=parseInt(cantidad) && unidad_medida != "servicio"){   
                    if ( unidad_medida == "gramos") {
                        precio_axu = precio_venta/1000;
                        subtotal[cont]=(cantidad*precio_axu);
                    }
                    if ( unidad_medida == "unidad"){
                        subtotal[cont]=(cantidad*precio_venta);
                    }
                    total=total+subtotal[cont];
                    var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input id="art_'+cont+'" type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><label id="label_'+cont+'">'+cantidad+'</label></td><input id="can_'+cont+'" type="hidden" name="cantidad[]" value="'+cantidad+'"><input type="hidden" name="unidad_medida[]" value="'+unidad_medida+'"><td><label id="preciolabel_'+cont+'">'+parseFloat(precio_venta).toFixed(0)+'</label></td><input id="precio_'+cont+'" type="hidden" name="precio_venta[]" value="'+parseFloat(precio_venta).toFixed(0)+'"><td><label>'+parseFloat(impuesto).toFixed(0)+'</label></td><input type="hidden" name="impuesto[]" value="'+parseFloat(impuesto).toFixed(0)+'"><input id="subtotal_'+cont+'" type="hidden" name="subtotal" value="'+subtotal[cont]+'"><td id="tdsubtotal_'+cont+'" align="right">Gs/. '+parseFloat(subtotal[cont]).toFixed(0)+'</td></tr>';
                    cont=cont+1;
                    cont_item = cont_item + 1;
                    totales();
                    evaluar();
                    limpiar();
                    $('#detalles').prepend(fila);   
                }
                else{
                    if(unidad_medida != "servicio"){
                        alert ('La cantidad a vender supera el stock');
                    }
                }
                if (unidad_medida == "servicio"){   
                    subtotal[cont]=(cantidad*precio_venta);
                    total=total+subtotal[cont];
                    var fila='<tr class="selected" id="fila'+cont+'"><td><button type="button" class="btn btn-warning" onclick="eliminar('+cont+');">X</button></td><td><input id="art_'+cont+'" type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td><td><label id="label_'+cont+'">'+cantidad+'</label></td><input id="can_'+cont+'" type="hidden" name="cantidad[]" value="'+cantidad+'"><input type="hidden" name="unidad_medida[]" value="'+unidad_medida+'"><td><label id="preciolabel_'+cont+'">'+parseFloat(precio_venta).toFixed(0)+'</label></td><input id="precio_'+cont+'" type="hidden" name="precio_venta[]" value="'+parseFloat(precio_venta).toFixed(0)+'"><td><label>'+parseFloat(impuesto).toFixed(0)+'</label></td><input type="hidden" name="impuesto[]" value="'+parseFloat(impuesto).toFixed(0)+'"><input id="subtotal_'+cont+'" type="hidden" name="subtotal" value="'+subtotal[cont]+'"><td id="tdsubtotal_'+cont+'" align="right">Gs/. '+parseFloat(subtotal[cont]).toFixed(0)+'</td></tr>';
                    cont=cont+1;
                    cont_item = cont_item + 1;
                    totales();
                    evaluar();
                    limpiar();
                    $('#detalles').prepend(fila);   
                }
            }
            else{
                alert("Error al ingresar el detalle de la venta, revise los datos del artículo");
                }
            }
        }else{
            alert('SUPERO LA CANTIDAD MAXIMA DE ITEM');
        }
    }

    function limpiar(){
        $("#buscar").val('');
        document.getElementById('buscar').focus();
    }
    function totales(){
        $("#total").html("Gs/ "+$.number(total));
        $("#total_venta").val(total.toFixed(0));
        //contador de item en grilla
        $("#pitem").val(cont_item);

        $("#total2").html("Gs/ "+$.number(total));
        
        total_pagar=total;
       
        $("#total_pagar").html("Gs/. " + total_pagar.toFixed(0));

        //cantidad de facturas math.ceil devuelve El número entero más pequeño mayor o igual que el número dado.
        cant_facturas = Math.ceil(cont_item/15);
        $("#pcant_facturas").html("Cant. Facturas: "+$.number(cant_facturas));
    }
    function evaluar(){    
        if (total>0){
            $("#guardar").show();
        }
        else{
            $("#guardar").hide(); 
        }
    }
    function eliminar(index){
        total=total-subtotal[index]; 
        $("#fila" + index).remove();
        //eliminar contador de item
        cont_item = cont_item - 1; 
        totales();
        evaluar();
    }
    function validacion() {
        
        if ($("#importe").val() < total) {
            // Si no se cumple la condicion...
            alert('[ERROR] El importe es menor a la venta...');
            return false;
        }else
        {
           alert ('La venta ya fue registrada...');
           return true;
        }
        // Si el script ha llegado a este punto, todas las condiciones
        // se han cumplido, por lo que se devuelve el valor true
    
    }

$('#liVentas').addClass("treeview active");
$('#liVentass').addClass("active");
  
</script>
@endpush
@endsection
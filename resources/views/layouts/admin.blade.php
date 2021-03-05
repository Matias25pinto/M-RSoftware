<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <title>M&R|SOFTWARE</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}">
   
    <!-- daterangepicker. -->
    <link rel="stylesheet" href="{{asset('bootstrap-daterangepicker/daterangepicker.css')}}">
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="{{url('home')}}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>M&R</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>M&R Software</b>
          </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <small class="bg-red">Online</small>
                  <span class="hidden-xs">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <p>{{ Auth::user()->name }}</p>
                    
                    <p>{{ Auth::user()->email }}</p>
                    <p>{{ Auth::user()->permisos }}</p>
                    <p>
                      <small>Copyright © 2019 M&R DESARROLLO SOFTWARE. All rights reserved.</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-right">
                      <a href="{{url('/logout')}}" class="btn btn-default btn-flat">Cerrar Sesión</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
 
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
                    
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            
            <li id="liEscritorio">
              <a href="{{url('home')}}">
                <i class="fa fa-dashboard"></i> <span>Escritorio</span>
              </a>
            </li>

            <li id="liAlmacen" class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Almacén</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liArticulos"><a href="{{url('almacen/articulo')}}"><i class="fa fa-circle-o"></i> Artículos</a></li>
                <li id="liCategorias"><a href="{{url('almacen/categoria')}}"><i class="fa fa-circle-o"></i> Categorías</a></li>
                <li id="liajustes"><a href="{{url('almacen/ajuste')}}"><i class="fa fa-circle-o"></i> Ajuste</a></li>
              </ul>
            </li>
            <li  id="liCompras" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Compras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liIngresos" ><a href="{{url('compras/ingreso')}}"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li id="liProveedores"><a href="{{url('compras/proveedor')}}"><i class="fa fa-circle-o"></i> Proveedores</a></li>
              </ul>
            </li>
            <li id="liVentas" class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart"></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liVentass"><a href="{{url('ventas/venta')}}"><i class="fa fa-circle-o"></i> Ventas</a></li>
                <li id="liClientes"><a href="{{url('ventas/cliente')}}"><i class="fa fa-circle-o"></i> Clientes</a></li>
                <li id="liCajas"><a href="{{url('ventas/caja')}}"><i class="fa fa-circle-o"></i> Caja</a></li>
                 <li id="livendedores"><a href="{{url('ventas/vendedores')}}"><i class="fa fa-circle-o"></i>Vendedores</a></li>
              </ul>
            </li>

            
            <li id="licontabilidad" class="treeview">
              <a href="#">
                <i class="fa fa-pie-chart"></i>
                <span>Reportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a> 
              <ul class="treeview-menu">
                <li id="lifacturasventas"><a href="{{url('cargar/ventas')}}"><i class="fa fa-circle-o"></i> Cargar Ventas</a></li>
                <li id="lirportespdf"><a href="{{url('reportes/pdf/')}}"><i class="fa fa-circle-o"></i> Reportes PDF</a></li>
              </ul>
            </li>
          <li id="lipagos" class="treeview">
              <a href="#">
                <i class="fa fa-money"></i>
                <span>Pagos de Salario</span>
                 <i  class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
              <li id="pagocomision"><a href="{{url('pago/salario')}}"><i class="fa fa-circle-o"></i> Lista de pagos</a></li>
                </li>
              </ul>
            </li>      
            <li id="liAcceso" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Acceso</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liUsuarios"><a href="{{url('seguridad/usuario')}}"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                
              </ul>
            </li>
             
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
       <!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
          
          <div class="row ">
            <div class="col-md-12">
              <div class="box ">
              <!--Contenido-->
                              
                <!--Fin Contenido-->
                <div class="box-header with-border  oculto-impresion">
                  <h3 class="box-title oculto-impresion"">Sistema de Ventas</h3>
                  <div class="box-tools pull-right oculto-impresion"">
                    <button class="btn btn-box-tool " data-widget="collapse"><i class="fa fa-minus"></i></button>
                    
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  	<div class="row">
	                  	<div class="col-md-12">
		                          <!--Contenido-->
                              @yield('contenido')
		                          <!--Fin Contenido-->
                           </div>
                        </div>
		                    
                  		</div>
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      <footer class="main-footer oculto-impresion">
        <div class="pull-right hidden-xs">
          <b>Version</b> 3.6
        </div>
        <strong>Copyright &copy; 2019 <a href="#">M&R DESARROLLO SOFTWARE</a>.</strong> All rights reserved.
      </footer>

      
    <!-- jQuery 2.1.4 -->
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    <script src="{{asset('js/bower.json')}}"></script>
    <script src="{{asset('js/df-number-format.jquery.json')}}"></script>
    <script src="{{asset('js/jquery.number.js')}}"></script>
    <script src="{{asset('js/jquery.number.min.js')}}"></script>

    @stack('scripts')
    <!-- Bootstrap 3.3.5 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/app.min.js')}}"></script>
     <!-- daterangepicker. -->
     <script src="{{asset('moment/min/moment.min.js')}}"></script>
     <script src="{{asset('bootstrap-daterangepicker/daterangepicker.js')}}"></script>
     
   <input type="hidden" name="permisos" id="permisos" value="{{ Auth::user()->permisos }}" > 
<script>
  $(document).ready(function() {
  if ($("#permisos").val() == "administrador")
    {
        $("#liAlmacen").show();
        $("#liCompras").show();
        $("#liVentas").show();
        $("#liReportes").show();
        $("#liAcceso").show();
        $("#lipagos").show();
        $("#livendedores").show();
         
    }
    if ($("#permisos").val() == "deposito")
    {
        $("#liAlmacen").show();
        $("#liCompras").show(); 
        $("#liVentas").hide();
        $("#liReportes").hide();
        $("#liAcceso").hide();
        $("#lipagos").hide();
        $("#livendedores").hide();

         
    }
     if ($("#permisos").val() == "ventas")
    {
        $("#liAlmacen").hide();
        $("#liCompras").hide();
        $("#liVentas").show();
        $("#lipagos").show();
        $("#liReportes").hide();
        $("#liAcceso").hide();
        $("#livendedores").hide();
        $("#lipagos").hide();


         
    }
});

</script>

    
  </body>
</html>

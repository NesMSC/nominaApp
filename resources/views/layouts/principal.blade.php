<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calculos Salariales</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Style theme  -->
  <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">

  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">


</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Configuraciones</a>
      </li> -->
    </ul>



    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-user" aria-hidden="true"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownUser">
          <a class="dropdown-item" href="{{ route('logout') }}" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-lock"></i> Cerrar sesión</a>
        </div>      
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <div class="app-body">
    @include('template.sidebar')
    <!-- Contenido Principal -->
        @include('contenido.contenido')
    <!-- /Fin del contenido principal -->
  </div>
</div>
<!-- ./wrapper -->
  <script src="{{asset('js/app.js')}}"></script>      
  <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button)

  </script>
  <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
  <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.js')}}"></script>


</body>
</html>

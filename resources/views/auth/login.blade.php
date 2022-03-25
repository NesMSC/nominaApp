<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calculos Salariales</title>

  <link rel="stylesheet" href="css/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Style theme  -->
  <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">

</head>
<body class="hold-transition login-page">
<div class="wrapper">
  <div class="login-box">
    <div class="login-logo mt-5">
        <img width="70" src="img/logo-upteb.png" alt="LogoUPT">
        <a href="/login">
          UPTEB <br>
          <b>Calculos Salariales </b>
      </a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">INICIAR SESIÓN</p>
        @if(!$condicion)
          <div class="alert alert-danger" role="alert">
            El usuario ingresado está deshabilitado
          </div>
        @endif
        <form action="{{route('login')}}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" placeholder="Usuario">
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="fas fa-user"></i>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input name="password" type="password" class="form-control @error('name') is-invalid @enderror" placeholder="Contraseña">
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="fas fa-lock"></i>
              </div>
            </div>
          </div>
          @if(Session::has('message'))
          <div class="container">
            <div class="alert alert-{{Session::get('typealert')}}">
              <span>{{Session::get('message')}}</span><br>
              @if($errors->any())
                    @foreach($errors->all() as $error)
                    <span>*{{$error}}</span>
                    @endforeach
               @endif
            </div>
          </div>
          @endif
          @error('name')
            <div class="text-danger">
                Usuario o contraseña inválida.
            </div>
          @enderror
          <div class="row justify-content-center">
            <!-- /.col -->
            <div class="col-4 align-self-center mb-2">
              <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="/recuperar">Recuperar contraseña</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
<!-- /.login-box -->
</div>
<!-- ./wrapper -->  
  
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('js/adminlte.js')}}"></script>
  <script>
    $('.alert').slideDown();
    setTimeout(function(){$('.alert').slideUp()}, 2000)
  </script>
</body>
</html>
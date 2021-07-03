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

	<div class="login-box">
		<div class="login-logo">
		    <a href="/login">
		    	UPTEB <br>
		    	<b>Calculos Salariales </b>
			</a>
		</div>
	  <!-- /.login-logo -->
	  <div class="card">
	    <div class="card-body login-card-body">
	      <p class="login-box-msg">Se enviará un código de seguridad a su correo electrónico.</p>

	    <form action="{{route('sendRecover')}}" method="post">
	    	@csrf
	        <div class="input-group mb-3">
	          <input type="email" name="email" class="form-control" placeholder="Email">
	          <div class="input-group-append">
	            <div class="input-group-text">
	              <span class="fas fa-envelope"></span>
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
	        <div class="row">
	          <div class="col-12">
	            <button type="submit" class="btn btn-primary btn-block">Enviar código</button>
	          </div>
	          <!-- /.col -->
	        </div>
	    </form>
	        <div class="row mt-2">
		        <div class="col-12">
		          	<a href="/login" class="btn btn-success btn-block">Iniciar sesión</a>
		        </div>
	        </div>
	    </div>
	    <!-- /.login-card-body -->
	  </div>
	</div>
	<!-- /.login-box --> 
  
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
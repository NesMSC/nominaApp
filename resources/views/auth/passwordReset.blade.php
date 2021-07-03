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
	      <p class="login-box-msg">Ingresa tu nueva contraseña.</p>

	    <form action="{{route('updateNewPassword')}}" method="post">
	    	@csrf
	        <div class="input-group mb-3">
	        	<input type="hidden" name="id" value="{{$id}}">
	          <input type="password" id="contraseña" name="contraseña" class="form-control" placeholder="Contraseña nueva" required="required">
	          <div class="input-group-append">
	            <div class="input-group-text">
	              <span class="fas fa-user"></span>
	            </div>
	          </div>
	        </div>
	        <div class="input-group mb-3">
	          <input type="password" id="confirm" name="confirmacion" class="form-control" placeholder="Confirmar contraseña" required="required">
	          <div class="input-group-append">
	            <div class="input-group-text">
	              <span class="fas fa-user"></span>
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
	            <button type="submit" class="btn btn-primary btn-block">Confirmar</button>
	          </div>
	          <!-- /.col -->
	        </div>
	    </form>
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
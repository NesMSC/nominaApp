@extends('emails.recover')

@section('content')
  <p>Hola <strong>{{$nombres}}</strong></p>

  <p>Este correo electrónico le permitirá reestablecer su contraseña.</p>

  <p>Su código de verificación es: </p>

  <h2>{{$code}}</h2>

  <p>Para continuar, haga clic en el siguiente boton:</p>

  <p><a href="{{url('/resetPassword/?email='.$email)}}" style="
    display: inline-block;
    background-color: #000;
    color: #fff;
    padding: 8px;
    border-radius: 4px;
    text-decoration: none;
  ">Reestablecer contraseña</a></p>
@stop
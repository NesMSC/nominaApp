<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>  
    <style type="text/css">
    	section{
    		width: 100%;
    		margin: 5px auto;
    	}

    	body{
    		font-family: 'Source Sans Pro', sans-serif;
    		font-size: 14px;
    	}

		img{
			width: 70px;
		}

		.datoPago{
			text-align: center;
		}
		
		.title{
			display:block;
			width: 100%;
		}

		.logo{
			width: 100%;
			display: block;
		}

		.pagos{
			margin-top: 20px;
		}
    </style>
    <body>
    	<header>
			<div class="logo">
				<img src="img/logo-upteb.png" alt="LogoUPT">
			</div>
			<div align="center" class="title">
				<h2><b>REPORTE DE PAGO</b></h2>
			</div>
		</header>
    	<section>
     		@if($datosEmpleado->tipoPersonal ==  "Administrativo")
    		<table width="100%">
                <tbody>
                    <tr>
	                    <td>
	                    	Beneficiario: {{$datosEmpleado->nombres.' '.$datosEmpleado->apellidos}}
	                    	<br>
							Cedula: {{$datosEmpleado->cedula}}
							<br>
							Cargo: {{$datosEmpleado->grado.' '.$datosEmpleado->nivel}}
	                    </td>
	                    <td>
	                    	Tipo de personal: {{$datosEmpleado->tipoPersonal}}
	                    	<br>
	                    	Departamento: {{$datosEmpleado->departamento->nombre}}
	                    	<br>
	                    	Dias trabajados: 15
	                	</td>
                    </tr>
                </tbody>
    		</table>
    		@elseif($datosEmpleado->tipoPersonal == "Docente")
    		<table width="100%">
                <tbody>
                    <tr>
	                    <td>
	                    	Beneficiario: {{$datosEmpleado->nombres.' '.$datosEmpleado->apellidos}}
	                    	<br>
							Cedula: {{$datosEmpleado->cedula}}
							<br>
							Categoria: {{$docente->categoria}}
							<br>
							Dedicación: {{$docente->dedicacion}}
	                    </td>
	                    <td>
	                    	Tipo de personal: {{$datosEmpleado->tipoPersonal}}
	                    	<br>
	                    	PNF: {{$docente->pnf}}
	                    	<br>
	                    	Dias trabajados: 15
	                	</td>
                    </tr>
                </tbody>
    		</table>
    		@else
    		<table width="100%">
                <tbody>
                    <tr>
	                    <td>
	                    	Beneficiario: {{$datosEmpleado->nombres.' '.$datosEmpleado->apellidos}}
	                    	<br>
							Cedula: {{$datosEmpleado->cedula}}
							<br>
							Cargo: {{'Obrero grado'.$datosEmpleado->grado}}
	                    </td>
	                    <td>
	                    	Tipo de personal: {{$datosEmpleado->tipoPersonal}}
	                	</td>
                    </tr>
                </tbody>
    		</table>
    		@endif
			<table class="pagos" width="100%" border="1" cellspacing="0">
				<thead>
					<tr>
						<th>Código</th>
						<th>Sueldo</th>
						<th>Salario Normal</th>
						<th>Total Neto</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pagos as $value)
					<tr>
						<td class="datoPago">{{$value->codigo}}</td>
						<td class="datoPago">{{$value->sueldo}}</td>
						<td class="datoPago">{{$value->pago["salarioNormal"]}}</td>
						<td class="datoPago">{{$value->pago["totalNeto"]}}</td>
						<td class="datoPago">{{$value->fecha}}</td>
					</tr>
					@endforeach
					<tr>
						<td></td>
						<td class="datoPago">
							<strong>
								{{
									$total[2]
								}}
							</strong>
						</td>
						<td class="datoPago">
							<strong>
								{{
									$total[1]
								}}
							</strong>
						</td>
						<td class="datoPago">
							<strong>
								{{
									$total[0]
								}}
							</strong>
						</td>
						<td></td>

					</tr>
				</tbody>
			</table>
    	</section>
    </body>
</html>
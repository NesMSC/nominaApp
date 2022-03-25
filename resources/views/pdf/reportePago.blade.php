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
		
		.title{
			display:block;
			width: 100%;
		}

		.logo{
			width: 100%;
			display: block;
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
    		<table width="100%" border="0">
    			<tbody>
    				<tr style="background: #f2f2f2;">
                      <td>
                        <strong>Codigo:</strong> {{$datosEmpleado->codigo}}
                        
                      </td>
                      <td align="right">
                          Fecha: {{$datosEmpleado->fecha}}
                      </td>
                    </tr>
    			</tbody>
    		</table>
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
	                    	Departamento: {{$datosEmpleado->departamento}}
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
	                    	<br>
	                    	Dias trabajados: 15
	                	</td>
                    </tr>
                </tbody>
    		</table>
    		@endif
    		<table width="100%" border="0">
    			<tbody>
    				<tr style="background: #f2f2f2;">
                      <td>
                        <strong>Salario tabla: {{number_format($datosEmpleado->sueldo, 2)}}</strong>
                      </td>
                      <td align="right">
                      	<strong> Salario normal mensual: {{number_format($datosEmpleado->salarioNormal, 2)}}</strong>
                      </td>
                    </tr>
    			</tbody>
    		</table>
    		<table width="100%">
    			<tbody>
                    <tr style="background-color: #343a40">
                      <td colspan="6" align="center" style="color: #fff;">ASIGNACIONES</td>
                    </tr>
                    <tr>
                      <td colspan="4">Sueldo por dias trabajados</td>
                      <td colspan="2" align="right">{{number_format($datosEmpleado->sueldo/2, 2)}}</td>
                    </tr>
                    @if($datosEmpleado->quincena%2 == 0)
                    @foreach($asignaciones as $value)
	                    <tr>
	                      <td colspan="4">{{$value->concepto}}</td>
	                      <td colspan="2" align="right">{{number_format($value->valor, 2)}}</td>
	                    </tr>
                    @endforeach 
                    @endif
                    <tr>
                    	<td colspan="6"><hr></td>
                    </tr>
                    <!-- <tr>
                      <td colspan="4"><strong>Total de Beneficios:</strong></td>
                      <td colspan="2" align="right">{{number_format($datosEmpleado->sueldo/2, 2)}}</td>
                    </tr> -->
                  </tbody>
    		</table>
    	 	<table width="100%">
    			<tbody>
                    <tr style="background-color: #343a40">
                      <td colspan="6" align="center" style="color: #fff;">DEDUCCIONES</td>
                    </tr>
                    @if($datosEmpleado->quincena%2 == 0)
                    @foreach($deducciones as $value)
	                    <tr>
	                      <td colspan="4">{{$value->concepto}}</td>
	                      <td colspan="2" align="right">{{number_format($value->valor, 2)}}</td>
	                    </tr>
	                @endforeach
                    @endif
                    <tr>
                    	<td colspan="6"><hr></td>
                    </tr>
                    <!-- <tr>
                      <td colspan="4"><strong>Total de deducciones:</strong></td>
                      <td colspan="2" align="right"></td>
                    </tr> -->
                  </tbody>
    		</table>
    		<table width="100%">
    			<tbody>
                    <tr style="background-color: #343a40">
                      <td colspan="6" align="center" style="color: #fff;">DESCUENTOS</td>
                    </tr>
                    @if($datosEmpleado->quincena%2 == 0)
                    @foreach($descuentos as $value)
	                    <tr>
	                      <td colspan="4">{{$value->concepto}}</td>
	                      <td colspan="2" align="right">{{number_format($value->valor, 2)}}</td>
	                    </tr>
	                @endforeach
                    @endif
                    <tr>
                    	<td colspan="6"><hr></td>
                    </tr>
                    <!-- <tr>
                      <td colspan="4"><strong>Total de descuentos:</strong></td>
                      <td colspan="2" align="right"></td>
                    </tr> -->
                  </tbody>
    		</table>
    		<table width="100%">
    			<tbody>
	    			<tr>
	                    <td colspan="6"><hr></td>
	                </tr>
	                <!-- <tr>
	                	<td colspan="4"><strong>Total a pagar: </strong></td>
	                	<td colspan="2" align="right"><strong>{{number_format($datosEmpleado->sueldo/2, 2)}}</strong></td>
	                </tr> -->
    			</tbody>
    		</table>
    	</section>
    </body>
</html>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Nomina;
use App\Empleado;
use App\Pago;
use App\Salario;

class nominaController extends Controller
{
    public function index(Request $request)
    {
		
		$nominas = Nomina::orderBy('id', 'desc')
    					->paginate(10);
		
    	return [
          "pagination" => [
                "total" => $nominas->total(),
                "current_page" => $nominas->currentPage(),
                "per_page" => $nominas->perPage(),
                "last_page" => $nominas->lastPage(),
                "from" => $nominas->firstItem(),
                "to" => $nominas->lastPage()
            ],
            "nominas"=>$nominas
        ];
    }

    public function registrarNominas(Request $request)
    {

		$codigo = 'N'.substr($request->personal, 0, 1).'-'.$request->quincena.date('Y');

		if($this->verificarCodigo($codigo)){
			return response([
				'status' => 'error',
				'msg' => 'Esta nómina ya fue creada para este periodo'
			], 200);
		}

    	try{
    		DB::beginTransaction();
    		
    			$nomina = new Nomina;
    			$nomina->codigo = $codigo;
				$nomina->tipo = $request->tipo_nomina;
				$nomina->descripcion = $request->descripcion;
	    		$nomina->fecha = date('Y-m-d');
	    		$nomina->save();

	    		$empleados = Empleado::select('id', 'salario_id')
	    							->where('tipoPersonal', $request->personal)
	    							->get();

	    		//Agregar pagos
	    		for ($b=0; $b < count($empleados); $b++) {
	    			$pago = new Pago;
	    			$pago->id_empleado = $empleados[$b]->id;
	    			$pago->id_nomina = $nomina->id;
	    			$pago->sueldo = $this->salarioTabla($empleados[$b]->id, $empleados[$b]->salario_id);
	    			$pago->salarioNormal = $this->salarioNormal($pago->sueldo, $empleados[$b]->id);
	    			$pago->asignaciones = $this->asignaciones($empleados[$b]->id);
	    			$pago->deducciones = $this->deducciones($empleados[$b]->id);
	    			$pago->descuentos = $this->descuentos($empleados[$b]->id);
	    			$pago->save();
	    		};
    		
    		DB::commit();
    	}catch(Exception $e){
            DB::rollBack();
            return $e;
        }

		return response([
			'status' => 'success',
			'msg' => 'Nómina generada'
		], 200);
    }

	public function consultarNomina($id)
	{
		$array = [];
		$fecha = Nomina::find($id)->fecha;
		$nomina = Nomina::find($id)->pagos;

	 	 for ($i=0; $i < count($nomina); $i++) {
			$empleado = $this->datosPersona($nomina[$i]->id_empleado)->persona;

			$nombre = substr($empleado->nombres, 0, strpos($empleado->nombres, ' '));
			$apellido = substr($empleado->apellidos, 0, strpos($empleado->apellidos, ' '));
			$cedula = substr($empleado->cedula, 2);
			$pre_cedula = substr($empleado->cedula, 0, 1);
			$data = [
				"nombre" => ($nombre)?$nombre : $empleado->nombres,
				"apellido" => ($apellido)?$apellido : $empleado->apellidos,
				"cedula" => $cedula,
				"pre_cedula" => $pre_cedula,
				"banco" => $empleado->cuentaBancaria,
				"salario" => $nomina[$i]->sueldo,
				"salarioNormal" => $nomina[$i]->salarioNormal,
				"totalPrimas" => $this->totalPrimas($nomina[$i]->asignaciones),
				"totalRetencion" => $this->totalRetencion($nomina[$i]->deducciones),
				"totalAportes" => $this->totalAporte($nomina[$i]->deducciones),
				"totalDescuentos" => $this->totalDescuentos($nomina[$i]->descuentos),
				"netoAbonar" => 0,
			];

			$data["netoAbonar"] = ($data['salario'] + $data['totalPrimas']) - $data['totalRetencion'] - $data['totalDescuentos'];
			array_push($array, $data);
		};

		return ["fecha" => $fecha, "pagos" => $array];
	}

	private function datosPersona($id){
		return Empleado::find($id);
	}

	private function totalPrimas($primas){
		$total = 0;

		$data = json_decode($primas);

		for ($i=0; $i < count($data); $i++) { 
			$total += $data[$i]->valor;
		}

		return $total;
	}

	private function totalRetencion($retenciones){
		$total = 0;

		$data = json_decode($retenciones);

		for ($i=0; $i < count($data); $i++) {
			if($data[$i]->tipo == 'Retención'){
				$total += $data[$i]->valor;
			}
		}

		return $total;
	}

	private function totalAporte($aportes){
		$total = 0;

		$data = json_decode($aportes);

		for ($i=0; $i < count($data); $i++) {
			if($data[$i]->tipo == 'Aporte'){
				$total += $data[$i]->valor;
			}
		}

		return $total;
	}

	private function totalDescuentos($descuentos){
		$total = 0;

		$data = json_decode($descuentos);

		for ($i=0; $i < count($data); $i++) {
			$total += $data[$i]->valor;
		}

		return $total;
	}

    public function salarioTabla($id, $salario = false)
    {

		$empleado = Empleado::find($id);

		$salario = $empleado->salario;

		$tabulador = json_decode($salario->tabulador, true);

      switch ($salario->id) {
        	case 1:
        		$sueldo = $tabulador[$empleado->grado][$empleado->nivel-1];
        		break;
        	case 2:
        		$sueldo = $tabulador[$empleado->grado];
        		break;
        	case 3:
        		$docenteCat = DB::table('docente_cat')
        						->select('categoria', 'dedicacion')
        						->where('empleado_id', $id)
        						->first();

        		if (strpos($docenteCat->categoria, 'Auxiliar') !== false) {
        			
        			//Extrae el grado del str de categoria
        			$gradoAuxiliar = substr($docenteCat->categoria, -1);

        			$sueldo = $tabulador['Auxiliar Docente'][$docenteCat->dedicacion][$gradoAuxiliar-1];
        		}else{
        			$sueldo = $tabulador[$docenteCat->categoria][$docenteCat->dedicacion];
        		}
        		
        		break;
        }

        return $sueldo;
    }

    public function salarioNormal($sueldo, $id, $rtotal = false)
    {
		
     	$asig = json_decode($this->asignaciones($id, false));
    	$totalAsig = 0;

     	//Suma total de asignaciones
    	for ($i=0; $i < count($asig); $i++) { 

    		if ($asig[$i]->concepto == 'Prima de Profesionalización') {

    			$totalAsig += $this->primaProfesional($id, $sueldo);

    		}elseif($asig[$i]->tipo_valor == 'especifico'){

				$totalAsig += $asig[$i]->valor;

			}elseif ($asig[$i]->tipo_valor == 'U.T') {

    			$totalAsig += $this->calcUnidadTr($asig[$i]->valor);

    		}elseif ($asig[$i]->tipo_valor == '%' && $asig[$i]->concepto != 'Prima de Profesionalización' && $asig[$i]->concepto != 'Prima de Antiguedad') {
    			
				if($asig[$i]->tipo_valor_por == 'salario_tabla'){
					$totalAsig += ($sueldo*$asig[$i]->valor)/100;
				}elseif($asig[$i]->tipo_valor_por == 'salario_min_mensual'){
					$salarioMinMensual = $this->salarioMinimoMensual();

					$totalAsig += ($salarioMinMensual*$asig[$i]->valor)/100;
				}else{
					$totalAsig += ($asig[$i]->tipo_valor_por*$asig[$i]->valor)/100;
				}
    		};
    	}

		if($rtotal){return $totalAsig+$sueldo;}

    	$antiguedad = $this->primaAntiguedad($id, $totalAsig+$sueldo);

    	$salarioNormal = $totalAsig+$sueldo+$antiguedad;
    	return (string) $salarioNormal;

    }

    public function asignaciones($id, $calc = true)
    {
    	$empleado = Empleado::find($id);

		//Si es true, retorna las asignaciones calculadas
		if($calc){
			$asignaciones = $this->calcAsignacion($empleado);
		}else{
			//Si no, se devuelve el json de las asignaciones sin calcular
			$asignaciones = $empleado->beneficio;
		}
			
		return json_encode($asignaciones);
		
    }

	private function calcAsignacion($empleado)
	{
		$valores = [];

		$asignaciones = $empleado->beneficio;
		$empleado_id = $empleado->id;
		$salario = $this->salarioTabla($empleado->id, $empleado->salario_id);
 		//Recorrer el array de asignaciones
		for ($i=0; $i < count($asignaciones); $i++) { 
			//Calcular la asignacion
			$valor = 0;
			if ($asignaciones[$i]->concepto == 'Prima de Profesionalización') {

    			$valor = $this->primaProfesional($empleado->id, $salario); 

    		}elseif($asignaciones[$i]->tipo_valor == 'especifico'){

				$valor = $asignaciones[$i]->valor;

			}elseif ($asignaciones[$i]->tipo_valor == 'U.T') {

    			$valor = $this->calcUnidadTr($asignaciones[$i]->valor);

    		}elseif($asignaciones[$i]->concepto == 'Prima de Antiguedad'){

				$valor = $this->primaAntiguedad($empleado_id, $this->total($empleado_id, $salario));

			}elseif ($asignaciones[$i]->tipo_valor == '%' && $asignaciones[$i]->concepto != 'Prima de Profesionalización' && $asignaciones[$i]->concepto != 'Prima de Antiguedad'){

				if($asignaciones[$i]->tipo_valor_por == 'salario_tabla'){
					$valor = ($salario*$asignaciones[$i]->valor)/100;
				}elseif($asignaciones[$i]->tipo_valor_por == 'salario_min_mensual'){
					$salarioMinMensual = $this->salarioMinimoMensual();

					$valor = ($salarioMinMensual*$asignaciones[$i]->valor)/100;
				}else{
					$valor = ($asignaciones[$i]->tipo_valor_por*$asignaciones[$i]->valor)/100;
				}
				
			};

			$data = [
				'concepto' => $asignaciones[$i]->concepto,
				'valor' => $valor
			];

			
			//Insertar en el array valores
			array_push($valores, $data);
		}

		//Retornar
		return $valores;
	}

    public function descuentos($id)
    {

    	$empleado_desc = Empleado::find($id)->descuento;
		$decuentos = $this->calcDescuentos($empleado_desc, $this->salarioTabla($id));
    	return json_encode($decuentos);

    }

	private function calcDescuentos($desc, $salarioT){
		$arrayDesc = []; //Array filtrado
		$valores = []; //Valores ya calculados
        $total = 0;

		//Filtrar los descuentos
		for ($i=0; $i < count($desc); $i++) {

			($desc[$i]->tipo == 'Descuento')?  array_push($arrayDesc, $desc[$i])  : null;
		}

	 	//Calcular
         for ($i=0; $i < count($arrayDesc); $i++) {

            $calc = ($salarioT*$arrayDesc[$i]->porcentaje)/100;

            array_push($valores, ["concepto" => $arrayDesc[$i]->concepto, "valor" => $calc]);

            $total += $calc; 
        }

		return $valores;

	}

    public function deducciones($id)
    {
		$empleado = Empleado::find($id);
		$empleado_deduc = $this->calcDeducciones($empleado->descuento, $id); 
    	return json_encode($empleado_deduc);
    }

	private function calcDeducciones($deducciones, $id)
	{
		$arrayDeducciones = [];
		$valores = []; //Array de valores ya calculados
		$total = 0;
		$salario = $this->salarioTabla($id);
		$totalAsig = $this->total($id, $salario); 
		$totalAsig = $totalAsig + $this->primaAntiguedad($id, $totalAsig); //total de asignaciones + salario tabla

		//Filtrar las deducciones
		for ($i=0; $i < count($deducciones); $i++) {

			($deducciones[$i]->tipo != 'Descuento')? $arrayDeducciones[$i] = $deducciones[$i]  : null;
		}

		//Calcular el array de deducciones
		for ($i=0; $i < count($arrayDeducciones); $i++) {

            if ($arrayDeducciones[$i]->concepto =='S.S.O' || $arrayDeducciones[$i]->concepto == 'R.P.E') {
                $calc = $this->sso_rpe($arrayDeducciones[$i]->porcentaje, $totalAsig);
            }else if($arrayDeducciones[$i]->concepto == 'V.H' || $arrayDeducciones[$i]->concepto == 'F.J'){
                $calc = (($totalAsig*$arrayDeducciones[$i]->porcentaje)/100); //
            }else{
                $calc = (($salario*$arrayDeducciones[$i]->porcentaje)/100); //
            };

            array_push($valores, ["concepto" => $arrayDeducciones[$i]->concepto, "valor" => $calc, "tipo" => $arrayDeducciones[$i]->tipo]);
             
            $total += $calc;
        }

		//Calcular el total de deducciones
		//Retornar datos
		return $valores;
	}

    public function primaProfesional($id, $sueldo)
    {

    	$empleado = Empleado::select('instruccion')
	    							->where('id', $id)
	    							->first();

        $json = file_get_contents('json/primaProfesional.json');
        $porcentajes = json_decode($json, true);

        if (!isset($porcentajes[$empleado->instruccion])) {
        	return 0;
        }
        $prima = $porcentajes[$empleado->instruccion];

        return ($prima*$sueldo)/100;
    }

    public function primaAntiguedad($id, $total)
    {
    	$empleado_bene = Empleado::join('beneficio_empleado', 'empleados.id', 'beneficio_empleado.empleado_id')
    							->join('beneficios', 'beneficio_empleado.beneficio_id', 'beneficios.id')
    							->select('beneficio_id')
    							->where('empleado_id', $id)
    							->where('beneficios.concepto', 'Prima de Antiguedad')
    							->first();

    	if ($empleado_bene) {
    		$porcentaje = DB::table('beneficios')
				    		->select('valor')
				    		->where('concepto', 'Prima de Antiguedad')
				    		->first();
			$valor = (($total*$porcentaje->valor)/100)*$this->calcAñoServ($id);
			return  $valor;
    	}else{
    		return 0;
    	}

    }

    public function calcUnidadTr($cantidad)
    {
    	$unidad = DB::table('ind_economicos')
					->select('UnTributaria')
					->first();
		return $unidad->UnTributaria*$cantidad;
    }

	private function salarioMinimoMensual(){
		$salarioMinMensual = DB::table('ind_economicos')
					->select('salarioMin', 'cestaTicket')
					->first();

		return $salarioMinMensual->salarioMin + $salarioMinMensual->cestaTicket;
	}

    public function calcAñoServ($id)
    {
    	$empleado = Empleado::select('fechaIngreso')
    						->where('id', $id)
    						->first();

    	$año = substr($empleado->fechaIngreso, 0, 4);

    	$actual = getdate()["year"];

    	$añosServ = $actual-$año;

    	return $añosServ;
    }

	private function total($id, $salario){
		$total = $this->salarioNormal($salario, $id, true);
		return $total;
	}

	public function sso_rpe($porcentaje, $totalAsig)
    {

        $valor = ((($totalAsig * 12)/52)*$porcentaje/100)*4;
        
        return $valor;
    }

	private function verificarCodigo($code)
	{
		$nomina = Nomina::select('id')
						->where('codigo', $code)
						->first();
		
		
		return isset($nomina->id);
	}

	public function destroyNomina($id, Request $request)
	{	  
        if (!$request->ajax()) return redirect('/');

        DB::table('nominas')->delete($id);
	}

	public function generarTxt($id){
		
		$query = $this->consultarNomina($id);
		$data = $query['pagos'];
		$fecha = $query['fecha'];

		header('Content-disposition: attachment; filename='.$fecha.'.txt');

		// ONTNOM G200020709 0000077 000031719184500 VES 20210420

		//Valores fijos
		$encabezado = 'ONTNOMG200020709';

		//Cantidad de operaciones
		$operaciones = count($data);
		$encabezado .= $this->ceros("$operaciones", 7);
		
		//Monto total
		$total = $this->totalPagos($data);
 		$encabezado .= $this->ceros($total.'00', 15);

		//Valor fijo VES
		$encabezado .= 'VES';

		//Fecha
		$encabezado .= str_replace('-', '', $fecha);
 
		echo $encabezado . PHP_EOL;
		foreach ($data as $val) {

			$linea = $val['pre_cedula'];
			$linea .= $this->ceros($val['cedula'], 8);
			$linea .= $this->ceros($val['banco']['numero_cuenta'], 20);
			$linea .= $this->ceros(floor($val['netoAbonar']).'00', 11);
			$linea .= strtoupper($val['apellido']).' '.strtoupper($val['nombre']);
		
			for ($i = strlen($linea); $i < 80; $i++) { 
				$linea .= ' ';
			}

			echo $linea . PHP_EOL;
		}
	}

	private function ceros($cadena, $longitud){
		$ceros = '';
		for ($i = strlen($cadena); $i < $longitud; $i++) { 
			$ceros .= '0';
		}

		return $ceros.$cadena;
	}

	private function totalPagos($arr){
		$total = 0;

		for ($i=0; $i < count($arr); $i++) { 
			$total += $arr[$i]['netoAbonar'];
		}

		return floor($total);
	}
}
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
    	$nominas = Nomina::select('id', 'nombre', 'quincena', 'fecha')
    					->where('nombre', $request->nomina)
    					->orderBy('id', 'desc')
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
    	try{
    		DB::beginTransaction();
    		
    		$personal = ["Administrativo",  "Docente", "Obrero"];
    		$añoActual = getdate()['year'];
    		for ($i=0; $i < count($personal); $i++) { 
    			$nomina = new Nomina;
    			$nomina->quincena = $request->quincena;
	    		$nomina->fecha = '2020-11-28';
	    		$nomina->nombre = $personal[$i];
	    		$nomina->save();

	    		$empleados = Empleado::select('id', 'salario_id')
	    							->where('tipoPersonal', $personal[$i])
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
	    			$pago->codigo = 'RRHH-NA'.$request->quincena.'-'.$añoActual;
	    			$pago->save();
	    		};
    		};
    		



    		DB::commit();
    	}catch(Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function salarioTabla($id, $salario)
    {

        $tabulador = Salario::select('tabulador')
                          ->where('id', $salario)
                          ->first();
		$tabulador = json_decode($tabulador->tabulador, true);

		$empleado = Empleado::select('grado', 'nivel')
							->where('id', $id)
							->first();

     switch ($salario) {
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

    public function salarioNormal($sueldo, $id)
    {

    	$asig = json_decode($this->asignaciones($id));
    	$totalAsig = 0;

    	//Suma total de asignaciones
    	for ($i=0; $i < count($asig); $i++) { 

    		if ($asig[$i]->concepto == 'Prima de Profesionalización') {

    			$totalAsig += $this->primaProfesional($id, $sueldo);

    		};

    		if ($asig[$i]->tipo_valor == 'U.T') {

    			$totalAsig += $this->calcUnidadTr($asig[$i]->valor);

    		};

    		if ($asig[$i]->tipo_valor == '%' && $asig[$i]->concepto != 'Prima de Profesionalización' && $asig[$i]->concepto != 'Prima de Antiguedad') {
    			$totalAsig += ($sueldo*$asig[$i]->valor)/100;
    		};
    	}

    	$antiguedad = $this->primaAntiguedad($id, $totalAsig+$sueldo);

    	$salarioNormal = $totalAsig+$sueldo+$antiguedad;
    	return (string) $salarioNormal;

    }

    public function asignaciones($id)
    {
    	$empleado_bene = Empleado::find($id)->beneficio;
    	return json_encode($empleado_bene);
    }

    public function descuentos($id)
    {

    	$empleado_desc = Empleado::join('descuento_empleado', 'empleados.id', 'descuento_empleado.empleado_id')
    							->join('descuentos', 'descuento_empleado.descuento_id', 'descuentos.id')
    							->select('descuentos.concepto', 'descuentos.porcentaje')
    							->where('empleados.id', $id)
    							->where('descuentos.tipo', 'Descuento')
    							->get();

    	return json_encode($empleado_desc);

    }

    public function deducciones($id)
    {
    	$empleado_deduc = Empleado::join('descuento_empleado', 'empleados.id', 'descuento_empleado.empleado_id')
    						->join('descuentos', 'descuento_empleado.descuento_id', 'descuentos.id')
    						->select('descuentos.concepto', 'descuentos.porcentaje', 'descuentos.tipo')
    						->where('empleados.id', $id)
    						->where(function($query){
    							$query->where('descuentos.tipo', 'Aporte')
    								  ->orWhere('descuentos.tipo', 'Retención');
    						})
    						->get();
    	return json_encode($empleado_deduc);
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
}